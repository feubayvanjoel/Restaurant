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
        Schema::table('gestion_salle', function (Blueprint $table) {
            $table->integer('CAPACITE')->nullable()->after('NUMERO');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gestion_salle', function (Blueprint $table) {
            $table->dropColumn('CAPACITE');
        });
    }
};
