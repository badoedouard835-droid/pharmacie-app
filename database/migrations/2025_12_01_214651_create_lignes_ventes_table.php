<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Table lignes_ventes - Détails de chaque produit vendu dans une vente
     * Relation many-to-many entre ventes et produits
     */
    public function up(): void
    {
        Schema::create('lignes_ventes', function (Blueprint $table) {
            $table->id();
            
            // Clé étrangère vers ventes
            $table->foreignId('vente_id')->constrained('ventes')->onDelete('cascade');
            
            // Clé étrangère vers produits
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            
            $table->integer('quantite'); // Quantité vendue
            $table->decimal('prix_unitaire', 10, 2); // Prix unitaire au moment de la vente
            $table->decimal('remise', 10, 2)->default(0); // Remise sur cette ligne
            $table->decimal('montant_total', 10, 2); // Total de la ligne (quantité × prix - remise)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lignes_ventes');
    }
};