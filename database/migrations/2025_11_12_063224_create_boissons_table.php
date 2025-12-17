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
        Schema::create('boissons', function (Blueprint $table) {
            $table->id('IDBOISSONS');
            $table->string('NOM', 150);
            $table->integer('QUANTITE')->default(0);
            $table->decimal('PRIX', 10, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boissons');
    }
};
