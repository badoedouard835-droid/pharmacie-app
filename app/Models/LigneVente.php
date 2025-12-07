<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneVente extends Model
{
    use HasFactory;

    /**
     * ============================================
     * Nom de la table
     * ============================================
     */
    protected $table = 'lignes_ventes';

    /**
     * ============================================
     * Champs autorisés (Mass Assignment)
     * ============================================
     */
    protected $fillable = [
        'vente_id',         // ID de la vente
        'produit_id',       // ID du produit
        'quantite',         // Quantité vendue
        'prix_unitaire',    // Prix unitaire à la vente
        'remise',           // Remise appliquée
        'montant_total',    // Total de la ligne (quantité × prix - remise)
    ];

    /**
     * ============================================
     * Conversion automatique des types
     * ============================================
     */
    protected $casts = [
        'quantite' => 'integer',
        'prix_unitaire' => 'decimal:2',
        'remise' => 'decimal:2',
        'montant_total' => 'decimal:2',
    ];

    /**
     * ============================================
     * Relations
     * ============================================
     */

    /**
     * Une ligne appartient à une vente
     */
    public function vente()
    {
        return $this->belongsTo(Vente::class, 'vente_id');
    }

    /**
     * Une ligne concerne un produit
     */
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }

    /**
     * ============================================
     * Méthodes utiles
     * ============================================
     */

    /**
     * Calculer le montant total de la ligne
     */
    public function calculerMontant(): float
    {
        $sousTotal = $this->quantite * $this->prix_unitaire;
        return $sousTotal - $this->remise;
    }

    /**
     * Obtenir le nom du produit
     */
    public function nomProduit(): string
    {
        return $this->produit ? $this->produit->nom : 'Produit supprimé';
    }

    /**
     * ============================================
     * Booted : décrémente automatiquement le stock
     * ============================================
     */
    protected static function booted()
    {
        /**
         * Lorsqu'une ligne de vente est créée,
         * on décrémente automatiquement le stock du produit.
         */
        static::created(function ($ligne) {
            $produit = $ligne->produit;

            if ($produit) {
                $produit->quantite -= $ligne->quantite;
                $produit->save();
            }
        });
    }
}
