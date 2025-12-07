<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Produit extends Model
{
    use HasFactory;

    /**
     * Nom de la table
     */
    protected $table = 'produits';

    /**
     * Les attributs assignables en masse
     */
    protected $fillable = [
        'code_barre',
        'nom',
        'description',
        'categorie_id',
        'prix_achat',
        'prix_vente',
        'quantite_stock',
        'stock_minimum',
        'date_expiration',
        'laboratoire',
        'forme',
        'dosage',
        'photo',
        'statut',
    ];

    /**
     * Cast des attributs en types natifs
     */
    protected $casts = [
        'prix_achat' => 'decimal:2',
        'prix_vente' => 'decimal:2',
        'quantite_stock' => 'integer',
        'stock_minimum' => 'integer',
        'date_expiration' => 'date',
        'statut' => 'boolean',
    ];

    /**
     * ============================================
     * RELATIONS
     * ============================================
     */

    /**
     * Un produit appartient à une catégorie
     * Relation : Many-to-One (N:1)
     */
    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    /**
     * Un produit peut être dans plusieurs lignes de ventes
     */
    public function lignesVentes()
    {
        return $this->hasMany(LigneVente::class, 'produit_id');
    }
    public function aDesVentes(): bool
    {
        return $this->lignesVentes()->count() > 0;
   }

    /**
     * ============================================
     * ACCESSEURS
     * ============================================
     */

    /**
     * Retourne l'URL de la photo du produit
     */
    public function photoUrl(): string
    {
        if ($this->photo) {
            return Storage::url($this->photo);
        }
        
        // Image par défaut si pas de photo
        return 'https://via.placeholder.com/300x300.png?text=Pas+de+photo';
    }

    /**
     * Vérifie si le stock est faible (en dessous du seuil minimum)
     */
    public function isStockFaible(): bool
    {
        return $this->quantite_stock <= $this->stock_minimum;
    }

    /**
     * Vérifie si le produit est en rupture de stock
     */
    public function isRupture(): bool
    {
        return $this->quantite_stock <= 0;
    }

    /**
     * Vérifie si le produit est périmé
     */
    public function isPerime(): bool
    {
        if (!$this->date_expiration) {
            return false;
        }
        
        return Carbon::now()->greaterThan($this->date_expiration);
    }

    /**
     * Vérifie si le produit va bientôt expirer (dans moins de 30 jours)
     */
    public function isProchePeriemption(): bool
    {
        if (!$this->date_expiration) {
            return false;
        }
        
        $joursRestants = Carbon::now()->diffInDays($this->date_expiration, false);
        return $joursRestants >= 0 && $joursRestants <= 30;
    }

    /**
     * Retourne le nombre de jours avant expiration
     */
    public function joursAvantExpiration(): ?int
    {
        if (!$this->date_expiration) {
            return null;
        }
        
        return Carbon::now()->diffInDays($this->date_expiration, false);
    }

    /**
     * Calcule la marge bénéficiaire
     */
    public function marge(): float
    {
        if ($this->prix_achat == 0) {
            return 0;
        }
        
        return (($this->prix_vente - $this->prix_achat) / $this->prix_achat) * 100;
    }

    /**
     * Badge de statut du stock (HTML)
     */
    public function badgeStock(): string
    {
        if ($this->isRupture()) {
            return '<span class="badge bg-danger">Rupture</span>';
        } elseif ($this->isStockFaible()) {
            return '<span class="badge bg-warning text-dark">Stock faible</span>';
        } else {
            return '<span class="badge bg-success">En stock</span>';
        }
    }

    /**
     * Badge d'expiration (HTML)
     */
    public function badgeExpiration(): string
    {
        if ($this->isPerime()) {
            return '<span class="badge bg-danger">Périmé</span>';
        } elseif ($this->isProchePeriemption()) {
            return '<span class="badge bg-warning text-dark">Expire bientôt</span>';
        } else {
            return '<span class="badge bg-success">Valide</span>';
        }
    }

    /**
     * ============================================
     * SCOPES (Filtres réutilisables)
     * ============================================
     */

    /**
     * Filtre : Produits en stock
     */
    public function scopeEnStock($query)
    {
        return $query->where('quantite_stock', '>', 0);
    }

    /**
     * Filtre : Produits en rupture
     */
    public function scopeRupture($query)
    {
        return $query->where('quantite_stock', '<=', 0);
    }

    /**
     * Filtre : Stock faible
     */
    public function scopeStockFaible($query)
    {
        return $query->whereRaw('quantite_stock <= stock_minimum');
    }

    /**
     * Filtre : Produits périmés
     */
    public function scopePerime($query)
    {
        return $query->where('date_expiration', '<', Carbon::now());
    }

    /**
     * Filtre : Produits proche de l'expiration
     */
    public function scopeProchePeriemption($query)
    {
        $dans30Jours = Carbon::now()->addDays(30);
        return $query->whereBetween('date_expiration', [Carbon::now(), $dans30Jours]);
    }

    /**
     * Filtre : Recherche par nom, code-barre ou laboratoire
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nom', 'LIKE', "%{$search}%")
              ->orWhere('code_barre', 'LIKE', "%{$search}%")
              ->orWhere('laboratoire', 'LIKE', "%{$search}%");
        });
    }
    
}