<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Table categories - Classification des produits pharmaceutiques
     * Exemples: Antibiotiques, Antalgiques, Vitamines, etc.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Nom de la catégorie
            $table->text('description')->nullable(); // Description optionnelle
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};