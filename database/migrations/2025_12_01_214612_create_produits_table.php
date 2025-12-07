<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Table produits - Stocke tous les produits pharmaceutiques
     * Gère le stock, prix, dates d'expiration, photos, etc.
     */
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('code_barre', 191)->unique()->nullable(); // Limité à 191 caractères pour UTF8MB4
            $table->string('nom'); // Nom du produit
            $table->text('description')->nullable(); // Description détaillée
            
            // Clé étrangère vers la table categories
            $table->foreignId('categorie_id')->constrained('categories')->onDelete('cascade');
            
            $table->decimal('prix_achat', 10, 2)->default(0); // Prix d'achat
            $table->decimal('prix_vente', 10, 2); // Prix de vente
            $table->integer('quantite_stock')->default(0); // Quantité en stock
            $table->integer('stock_minimum')->default(5); // Seuil d'alerte
            $table->date('date_expiration')->nullable(); // Date d'expiration
            $table->string('laboratoire')->nullable(); // Nom du fabricant
            $table->string('forme')->nullable(); // Forme: comprimé, sirop, injection, etc.
            $table->string('dosage')->nullable(); // Dosage du médicament
            $table->string('photo')->nullable(); // Chemin de la photo
            $table->boolean('statut')->default(true); // Disponible/Indisponible
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
