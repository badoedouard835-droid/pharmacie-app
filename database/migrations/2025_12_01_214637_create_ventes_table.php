<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Table ventes - Enregistre chaque transaction de vente (facture)
     * Relation avec clients (optionnel) et users (vendeur)
     */
    public function up(): void
    {
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_vente', 191)->unique(); // Numéro de facture unique limité à 191 caractères
            
            // Clé étrangère vers clients (nullable pour ventes sans client enregistré)
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('set null');
            
            // Clé étrangère vers users (vendeur qui a effectué la vente)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->dateTime('date_vente'); // Date et heure de la vente
            $table->decimal('montant_total', 10, 2); // Montant total TTC
            $table->decimal('montant_ht', 10, 2)->default(0); // Montant HT
            $table->decimal('montant_tva', 10, 2)->default(0); // Montant TVA
            $table->decimal('remise', 10, 2)->default(0); // Remise appliquée
            $table->enum('mode_paiement', ['especes', 'carte', 'mobile_money', 'cheque'])->default('especes');
            $table->enum('statut', ['en_cours', 'validee', 'annulee'])->default('validee');
            $table->text('remarques')->nullable(); // Commentaires
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};
