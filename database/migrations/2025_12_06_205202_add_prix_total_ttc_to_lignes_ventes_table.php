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
        Schema::table('lignes_ventes', function (Blueprint $table) {
            // Ajout de la colonne prix_total_ttc
            $table->decimal('prix_total_ttc', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lignes_ventes', function (Blueprint $table) {
            // Suppression de la colonne prix_total_ttc
            $table->dropColumn('prix_total_ttc');
        });
    }
};
