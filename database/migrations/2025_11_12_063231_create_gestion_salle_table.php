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
        Schema::create('gestion_salle', function (Blueprint $table) {
            $table->id('IDTABLE');
            $table->integer('NUMERO')->nullable();
            $table->string('STATUT', 50)->default('Libre');
            $table->unique('NUMERO', 'UK_GESTION_SALLE_NUMERO');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestion_salle');
    }
};
