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
        Schema::create('horaire_reservation', function (Blueprint $table) {
            $table->integer('idHoraireReservation', true);
            $table->integer('idClient');
            $table->enum('statut', ['ACTIVE', 'ANNULEE', 'TERNINEE', 'SUPPRIMEE'])->default('ACTIVE');
            $table->dateTime('echeance')->nullable();
            $table->integer('nombre_personne')->nullable();
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_fin')->nullable();
            $table->dateTime('creer_le')->useCurrent();
            $table->integer('idTable')->nullable();

            $table->index('idClient', 'IDX_HORAIRE_RESERVATION_CLIENT');
            $table->index('idTable', 'IDX_HORAIRE_RESERVATION_TABLE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horaire_reservation');
    }
};
