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
        Schema::create('composer', function (Blueprint $table) {
            $table->unsignedBigInteger('IDPLATS');
            $table->unsignedBigInteger('IDCOMMANDE');
            $table->integer('NBPLATS')->default(1);
            $table->primary(['IDPLATS', 'IDCOMMANDE']);
            $table->index('IDCOMMANDE', 'IDX_COMPOSER_COMMANDE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('composer');
    }
};
