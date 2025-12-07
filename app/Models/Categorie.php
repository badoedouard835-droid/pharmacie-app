<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    /**
     * Nom de la table dans la base de données
     */
    protected $table = 'categories';

    /**
     * Les attributs qui peuvent être assignés en masse
     */
    protected $fillable = [
        'nom',
        'description',
    ];

    /**
     * ============================================
     * RELATIONS
     * ============================================
     */

    /**
     * Une catégorie contient plusieurs produits
     * Relation : One-to-Many (1:N)
     */
    public function produits()
    {
        return $this->hasMany(Produit::class, 'categorie_id');
    }

    /**
     * ============================================
     * ACCESSEURS
     * ============================================
     */

    /**
     * Compte le nombre de produits dans cette catégorie
     */
    public function nombreProduits(): int
    {
        return $this->produits()->count();
    }
}