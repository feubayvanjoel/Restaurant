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
        Schema::create('contenir', function (Blueprint $table) {
            $table->unsignedBigInteger('IDBOISSONS');
            $table->unsignedBigInteger('IDCOMMANDE');
            $table->integer('NBBOISSONS')->default(1);
            $table->primary(['IDBOISSONS', 'IDCOMMANDE']);
            $table->index('IDCOMMANDE', 'IDX_CONTENIR_COMMANDE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contenir');
    }
};
