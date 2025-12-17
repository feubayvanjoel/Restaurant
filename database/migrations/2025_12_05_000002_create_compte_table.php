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
        Schema::create('compte', function (Blueprint $table) {
            $table->string('login', 100)->primary();
            $table->string('password', 255)->nullable();
            $table->integer('idProprietaire');
            $table->enum('role', ['ADMIN', 'CAISSIER', 'CUISINIER', 'SERVEUR', 'CLIENT']);
            $table->dateTime('creer_le')->useCurrent();
            
            $table->index('idProprietaire', 'IDX_COMPTE_PROPRIETAIRE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compte');
    }
};
