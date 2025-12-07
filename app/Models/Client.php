<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vente;


class Client extends Model
{
    use HasFactory;

    /**
     * ============================================
     * NOM DE LA TABLE
     * ============================================
     * Par défaut, Laravel utilise le pluriel du nom du modèle
     * Ici, il cherchera automatiquement la table "clients"
     */
    protected $table = 'clients';

    /**
     * ============================================
     * CHAMPS AUTORISÉS (Mass Assignment)
     * ============================================
     * Ces champs peuvent être remplis avec Client::create([...])
     */
    protected $fillable = [
        'numero_client',  // Numéro unique du client (CLI001, CLI002, etc.)
        'nom',            // Nom de famille
        'prenom',         // Prénom
        'telephone',      // Téléphone (unique)
        'email',          // Email (optionnel)
        'adresse',        // Adresse complète
        'ville',          // Ville
        'date_naissance', // Date de naissance
        'sexe',           // M ou F
        'type',           // particulier ou professionnel
        'remarques',      // Notes supplémentaires
    ];

    /**
     * ============================================
     * CONVERSION AUTOMATIQUE DES TYPES (Casting)
     * ============================================
     * Laravel convertit automatiquement ces champs
     */
    protected $casts = [
        'date_naissance' => 'date', // Convertit en objet Carbon (date)
    ];

    /**
     * ============================================
     * RELATIONS AVEC D'AUTRES TABLES
     * ============================================
     */

    /**
     * Un client peut avoir plusieurs ventes
     * Relation : 1 Client -> plusieurs Ventes
     * 
     * Utilisation : $client->ventes
     */
    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }

    /**
     * ============================================
     * MÉTHODES UTILES PERSONNALISÉES
     * ============================================
     */

    /**
     * Obtenir le nom complet du client
     * 
     * Utilisation : {{ $client->nomComplet() }}
     * Résultat : "Jean DUPONT"
     */
    public function nomComplet(): string
    {
        return $this->prenom . ' ' . strtoupper($this->nom);
    }

    /**
     * Calculer l'âge du client
     * 
     * Utilisation : {{ $client->age() }} ans
     */
    public function age(): ?int
    {
        if (!$this->date_naissance) {
            return null;
        }
        
        // Carbon permet de manipuler les dates facilement
        return $this->date_naissance->age;
    }

    /**
     * Obtenir le montant total des achats du client
     * 
     * Utilisation : {{ $client->totalAchats() }} FCFA
     */
    public function totalAchats(): float
    {
        // sum() : additionne tous les montants_total des ventes
        return $this->ventes()->sum('montant_total');
    }

    /**
     * Obtenir le nombre d'achats du client
     * 
     * Utilisation : {{ $client->nombreAchats() }} achats
     */
    public function nombreAchats(): int
    {
        return $this->ventes()->count();
    }

    /**
     * Obtenir la dernière date d'achat
     * 
     * Utilisation : {{ $client->dernierAchat() }}
     */
    public function dernierAchat(): ?string
    {
        $derniereVente = $this->ventes()->latest('date_vente')->first();
        
        if ($derniereVente) {
            return $derniereVente->date_vente->format('d/m/Y');
        }
        
        return null;
    }

    /**
     * Vérifier si le client est un bon client
     * (plus de 5 achats)
     */
    public function estBonClient(): bool
    {
        return $this->nombreAchats() >= 5;
    }

    /**
     * Générer un numéro de client unique
     * Format : CLI001, CLI002, CLI003, etc.
     * 
     * Utilisation : Client::genererNumero()
     */
    public static function genererNumero(): string
    {
        // Récupérer le dernier client créé
        $dernierClient = self::latest('id')->first();
        
        if ($dernierClient) {
            // Extraire le numéro du dernier client (CLI001 -> 001)
            $dernierNumero = (int) substr($dernierClient->numero_client, 3);
            // Incrémenter
            $nouveauNumero = $dernierNumero + 1;
        } else {
            // Si aucun client n'existe, commencer à 1
            $nouveauNumero = 1;
        }
        
        // Formater avec des zéros (001, 002, etc.)
        // str_pad($nouveauNumero, 3, '0', STR_PAD_LEFT)
        // 1 devient 001, 15 devient 015, 150 devient 150
        return 'CLI' . str_pad($nouveauNumero, 3, '0', STR_PAD_LEFT);
    }
}