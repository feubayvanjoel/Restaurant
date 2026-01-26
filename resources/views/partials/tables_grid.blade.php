<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4" id="table-grid-container">
    @foreach($tables as $table)
        <div class="relative p-4 rounded-lg text-center border-2 transition-all shadow-sm
                    @if($table->statut === 'Libre') bg-green-50 border-green-300
                    @elseif($table->statut === 'Occupee') bg-orange-50 border-orange-300
                    @else bg-blue-50 border-blue-300
                    @endif">
            
            <!-- Status Icon/Indicator -->
            <div class="absolute top-2 right-2">
                <span class="flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75
                        @if($table->statut === 'Libre') bg-green-400
                        @elseif($table->statut === 'Occupee') bg-orange-400
                        @else bg-blue-400
                        @endif"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3
                        @if($table->statut === 'Libre') bg-green-500
                        @elseif($table->statut === 'Occupee') bg-orange-500
                        @else bg-blue-500
                        @endif"></span>
                </span>
            </div>

            <div class="text-3xl mb-2">
                @if($table->statut === 'Libre') 🟢
                @elseif($table->statut === 'Occupee') 🟠
                @else 🔵
                @endif
            </div>
            
            <p class="font-bold text-lg text-gray-800">Table {{ $table->numero }}</p>
            
            <!-- Client Info -->
            @if($table->currentClient)
                <p class="text-xs font-semibold text-indigo-700 mt-1 truncate" title="{{ $table->currentClient }}">
                    👤 {{ $table->currentClient }}
                </p>
            @else
                <p class="text-xs text-gray-400 mt-1">-</p>
            @endif

            <!-- Stats -->
            <div class="text-xs mt-2 space-y-1 bg-white/50 p-2 rounded">
                <div class="flex justify-between">
                    <span>Capacité:</span>
                    <span class="font-mono">{{ $table->capacite }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Occupants:</span>
                    <span class="font-mono">{{ $table->occupants }}</span>
                </div>
                <div class="flex justify-between {{ $table->places_restantes > 0 ? 'text-green-700' : 'text-red-700' }}">
                    <span>Reste:</span>
                    <span class="font-bold font-mono">{{ $table->places_restantes }}</span>
                </div>
            </div>

            <!-- Countdown Timer (Only for Occupied with Expiry) -->
            @if($table->statut === 'Occupee' && $table->orderExpiry)
                <div x-data="{ 
                        expiry: {{ $table->orderExpiry }},
                        remaining: '',
                        timeUp: false,
                        init() {
                            this.update();
                            setInterval(() => this.update(), 1000);
                        },
                        update() {
                            const now = new Date().getTime();
                            const diff = this.expiry - now;
                            
                            if (diff < 0) {
                                this.remaining = '00:00:00';
                                this.timeUp = true;
                                return;
                            }
                            
                            const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                            const s = Math.floor((diff % (1000 * 60)) / 1000);
                            
                            this.remaining = `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                        }
                     }" 
                     class="mt-2 text-sm font-mono font-bold"
                     :class="timeUp ? 'text-red-600 animate-pulse' : 'text-gray-700'"
                >
                    <span x-text="timeUp ? 'TEMPS ÉCOULÉ' : remaining"></span>
                </div>
            @endif

            <!-- Actions (Optional) -->
            @if(isset($showActions) && $showActions)
                <div class="mt-3 pt-3 border-t grid grid-cols-1 gap-2">
            <!-- Actions (Optional) -->
            @if(isset($showActions) && $showActions)
                <div class="mt-3 pt-3 border-t grid grid-cols-1 gap-2">
                    @if($table->statut === 'Libre' || $table->statut === 'Reservee')
                        <button 
                            @click="$dispatch('open-occupy-modal', { tableId: '{{ $table->idTable }}', tableNum: '{{ $table->numero }}', capacity: {{ $table->capacite }} })"
                            class="w-full py-1 px-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-bold transition"
                        >
                            OCCUPER
                        </button>
                    @endif

                    @if($table->statut === 'Occupee')
                        <form method="POST" action="{{ route('tables.checkout', $table->idTable) }}" onsubmit="return confirm('Voulez-vous vraiment libérer cette table et clôturer les commandes associées ?');">
                            @csrf
                            <button type="submit" class="w-full py-1 px-2 bg-gray-500 hover:bg-gray-600 text-white rounded text-xs font-bold transition">
                                LIBÉRER
                            </button>
                        </form>
                    @endif
                </div>
            @endif
                </div>
            @endif
        </div>
    @endforeach
</div>
