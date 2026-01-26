<!-- Occupy Modal -->
<div x-data="{ 
        open: false, 
        tableId: null, 
        tableNum: '', 
        capacity: 0,
        orders: [],
        loading: false,
        selectedOrder: null,
        init() {
            window.addEventListener('open-occupy-modal', (e) => {
                this.tableId = e.detail.tableId;
                this.tableNum = e.detail.tableNum;
                this.capacity = e.detail.capacity;
                this.open = true;
                this.fetchOrders();
            });
        },
        fetchOrders() {
            this.loading = true;
            fetch(`/tables/${this.tableId}/eligible-orders`)
                .then(res => res.json())
                .then(data => {
                    this.orders = data;
                    this.loading = false;
                })
                .catch(err => {
                    console.error(err);
                    this.loading = false;
                });
        }
    }"
    x-show="open" 
    class="fixed inset-0 z-50 overflow-y-auto" 
    style="display: none;"
    x-transition
>
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="open = false"></div>

    <!-- Modal -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Occuper la Table <span x-text="tableNum"></span></h3>
                <button @click="open = false" class="text-gray-400 hover:text-gray-500">✕</button>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-500 mb-2">Capacité: <span x-text="capacity"></span> personnes</p>
                <p class="text-sm text-gray-600">Sélectionnez une commande à installer sur cette table :</p>
            </div>

            <!-- Loader -->
            <div x-show="loading" class="flex justify-center py-4">
                <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <!-- Order List -->
            <form :action="`/tables/${tableId}/assign`" method="POST">
                @csrf
                <div x-show="!loading" class="max-h-60 overflow-y-auto space-y-2 mb-4">
                    <template x-for="order in orders" :key="order.idCommande">
                        <label class="flex items-center p-3 border rounded cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" name="commande_id" :value="order.idCommande" required class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <div class="ml-3 flex-1">
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-900">
                                        <span x-text="order.client ? order.client.prenom + ' ' + order.client.nom : 'Client Inconnu'"></span>
                                    </span>
                                    <span class="text-xs font-bold bg-blue-100 text-blue-800 px-2 py-0.5 rounded" x-text="'#'+order.idCommande"></span>
                                </div>
                                <p class="text-sm text-gray-500">
                                    <span x-text="order.NB_PERSONNES"></span> pers • 
                                    <span x-text="new Date(order.horaire).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></span>
                                    <span x-show="order.table" class="text-orange-600 ml-1">(Actuellement Table <span x-text="order.table.numero"></span>)</span>
                                </p>
                            </div>
                        </label>
                    </template>
                    <div x-show="orders.length === 0" class="text-center text-gray-500 py-4">
                        Aucune commande éligible trouvée (taille ou statut).
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="open = false" class="btn btn-outline">Annuler</button>
                    <button type="submit" class="btn btn-primary" :disabled="orders.length === 0">Confirmer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Table Modal -->
<div x-data="{ open: false }"
     @open-create-table-modal.window="open = true"
     x-show="open" 
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;"
     x-transition
>
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="open = false"></div>

    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-md w-full p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Ajouter une Nouvelle Table</h3>
            
            <form action="{{ route('tables.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Numéro de Table</label>
                        <input type="text" name="numero" required class="input mt-1 w-full" placeholder="Ex: T25">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Capacité (Personnes)</label>
                        <input type="number" name="capacite" min="1" required class="input mt-1 w-full" placeholder="Ex: 4">
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="open = false" class="btn btn-outline">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>
