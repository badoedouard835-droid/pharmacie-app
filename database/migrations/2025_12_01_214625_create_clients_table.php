<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Table clients - Informations sur les clients de la pharmacie
     * Permet de suivre l'historique des achats
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('numero_client', 191)->unique(); // Numéro unique limité à 191 caractères
            $table->string('nom'); // Nom du client
            $table->string('prenom'); // Prénom
            $table->string('telephone', 191)->unique(); // Téléphone unique limité à 191 caractères
            $table->string('email')->nullable(); // Email optionnel
            $table->text('adresse')->nullable(); // Adresse complète
            $table->string('ville')->nullable(); // Ville
            $table->date('date_naissance')->nullable(); // Date de naissance
            $table->enum('sexe', ['M', 'F'])->nullable(); // Genre
            $table->enum('type', ['particulier', 'professionnel'])->default('particulier'); // Type de client
            $table->text('remarques')->nullable(); // Notes supplémentaires
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
