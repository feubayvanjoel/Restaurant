<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ticket', function (Blueprint $table) {
            $table->id('IDTICKET');
            $table->unsignedBigInteger('IDCOMMANDE');
            $table->decimal('PRIX', 12, 2)->default(0.00);
            $table->dateTime('DATETICKET')->useCurrent();
            $table->unique('IDCOMMANDE', 'UK_TICKET_COMMANDE');
            $table->index('IDCOMMANDE', 'IDX_TICKET_COMMANDE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket');
    }
};
