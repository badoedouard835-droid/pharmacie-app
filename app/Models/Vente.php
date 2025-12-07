<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    use HasFactory;

    /**
     * ============================================
     * NOM DE LA TABLE
     * ============================================
     */
    protected $table = 'ventes';

    /**
     * ============================================
     * CHAMPS AUTORISÉS (Mass Assignment)
     * ============================================
     */
    protected $fillable = [
        'numero_vente',      // Numéro unique de facture (V2024001, V2024002...)
        'client_id',         // ID du client (peut être null pour vente sans client)
        'user_id',           // ID du vendeur (utilisateur connecté)
        'date_vente',        // Date et heure de la vente
        'montant_total',     // Montant total TTC
        'montant_ht',        // Montant hors taxes
        'montant_tva',       // Montant de la TVA
        'remise',            // Remise appliquée
        'mode_paiement',     // especes, carte, mobile_money, cheque
        'statut',            // en_cours, validee, annulee
        'remarques',         // Notes supplémentaires
    ];

    /**
     * ============================================
     * CONVERSION AUTOMATIQUE DES TYPES
     * ============================================
     */
    protected $casts = [
        'date_vente' => 'datetime',  // Convertit en objet Carbon
        'montant_total' => 'decimal:2',
        'montant_ht' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'remise' => 'decimal:2',
    ];

    /**
     * ============================================
     * RELATIONS AVEC D'AUTRES TABLES
     * ============================================
     */

    /**
     * Une vente appartient à un client
     * Relation : Vente -> Client
     * 
     * Utilisation : $vente->client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Une vente appartient à un utilisateur (vendeur)
     * Relation : Vente -> User
     * 
     * Utilisation : $vente->user (pour savoir qui a fait la vente)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Une vente contient plusieurs lignes (produits)
     * Relation : 1 Vente -> plusieurs LignesVentes
     * 
     * Utilisation : $vente->lignesVentes
     */
    public function lignesVentes()
    {
        return $this->hasMany(LigneVente::class, 'vente_id');
    }

    /**
     * ============================================
     * MÉTHODES UTILES PERSONNALISÉES
     * ============================================
     */

    /**
     * Générer un numéro de vente unique
     * Format : V2024001, V2024002, etc.
     * 
     * Utilisation : Vente::genererNumero()
     */
    public static function genererNumero(): string
    {
        // Année en cours
        $annee = date('Y');
        
        // Récupérer la dernière vente de l'année
        $derniereVente = self::whereYear('created_at', $annee)
            ->latest('id')
            ->first();
        
        if ($derniereVente) {
            // Extraire le numéro (V2024001 -> 001)
            $dernierNumero = (int) substr($derniereVente->numero_vente, 5);
            $nouveauNumero = $dernierNumero + 1;
        } else {
            // Première vente de l'année
            $nouveauNumero = 1;
        }
        
        // Format : V2024001
        return 'V' . $annee . str_pad($nouveauNumero, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Obtenir le nom du client ou "Client non enregistré"
     */
    public function nomClient(): string
    {
        if ($this->client) {
            return $this->client->nomComplet();
        }
        
        return 'Client non enregistré';
    }

    /**
     * Obtenir le nombre de produits dans la vente
     */
    public function nombreProduits(): int
    {
        return $this->lignesVentes()->sum('quantite');
    }

    /**
     * Vérifier si la vente est validée
     */
    public function estValidee(): bool
    {
        return $this->statut === 'validee';
    }

    /**
     * Vérifier si la vente est annulée
     */
    public function estAnnulee(): bool
    {
        return $this->statut === 'annulee';
    }

    /**
     * Formater le mode de paiement
     */
    public function modePaiementFormate(): string
    {
        $modes = [
            'especes' => 'Espèces',
            'carte' => 'Carte bancaire',
            'mobile_money' => 'Mobile Money',
            'cheque' => 'Chèque',
        ];
        
        return $modes[$this->mode_paiement] ?? $this->mode_paiement;
    }
}
