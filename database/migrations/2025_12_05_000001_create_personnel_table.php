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
        Schema::create('personnel', function (Blueprint $table) {
            $table->integer('idPersonnel', true);
            $table->string('nom', 100)->nullable();
            $table->string('prenom', 100)->nullable();
            $table->enum('poste', ['ADMIN', 'CAISSIER', 'CUISINIER', 'SERVEUR']);
            $table->string('email', 100)->nullable();
            $table->string('numero', 50)->nullable();
            $table->string('adresse', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel');
    }
};
