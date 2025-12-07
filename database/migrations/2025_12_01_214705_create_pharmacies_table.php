<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ============================================
     * TABLE PHARMACIES - GÉOLOCALISATION
     * ============================================
     * Stocke les informations et coordonnées GPS des pharmacies
     */
    public function up(): void
    {
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->id();
            
            // === INFORMATIONS DE BASE ===
            $table->string('nom'); // Nom de la pharmacie
            $table->text('adresse'); // Adresse complète
            $table->string('ville'); // Ville
            $table->string('quartier')->nullable(); // Quartier/Secteur
            
            // === CONTACT ===
            $table->string('telephone'); // Téléphone
            $table->string('email')->nullable(); // Email (optionnel)
            
            // === COORDONNÉES GPS ===
            // latitude : 12.3714277 (Ouagadougou)
            // longitude : -1.5196603
            $table->decimal('latitude', 10, 8)->nullable(); // 10 chiffres dont 8 après la virgule
            $table->decimal('longitude', 11, 8)->nullable(); // 11 chiffres dont 8 après la virgule
            
            // === HORAIRES ===
            $table->time('horaire_ouverture')->nullable(); // Heure d'ouverture (ex: 08:00)
            $table->time('horaire_fermeture')->nullable(); // Heure de fermeture (ex: 20:00)
            $table->string('jours_ouverture')->nullable(); // Jours (ex: "Lundi-Samedi")
            
            // === INFORMATIONS COMPLÉMENTAIRES ===
            $table->boolean('pharmacie_garde')->default(false); // Est actuellement de garde ?
            $table->string('photo')->nullable(); // Photo de la pharmacie
            $table->text('description')->nullable(); // Description
            $table->boolean('statut')->default(true); // Active/Inactive
            
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pharmacies');
    }
};