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
        Schema::create('commande', function (Blueprint $table) {
            $table->id('IDCOMMANDE');
            $table->unsignedBigInteger('IDTABLE');
            $table->unsignedBigInteger('IDCLIENT');
            $table->dateTime('HORAIRE')->useCurrent();
            $table->string('STATUT', 50)->default('Confirmee');
            $table->index('IDTABLE', 'IDX_COMMANDE_TABLE');
            $table->index('IDCLIENT', 'IDX_COMMANDE_CLIENT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commande');
    }
};
