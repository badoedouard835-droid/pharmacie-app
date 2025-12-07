<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacie extends Model
{
    use HasFactory;

    /**
     * ============================================
     * NOM DE LA TABLE
     * ============================================
     */
    protected $table = 'pharmacies';

    /**
     * ============================================
     * CHAMPS AUTORISÉS
     * ============================================
     */
    protected $fillable = [
        'nom',
        'adresse',
        'ville',
        'quartier',
        'telephone',
        'email',
        'latitude',
        'longitude',
        'horaire_ouverture',
        'horaire_fermeture',
        'jours_ouverture',
        'pharmacie_garde',
        'photo',
        'description',
        'statut',
    ];

    /**
     * ============================================
     * CONVERSION DES TYPES
     * ============================================
     */
    protected $casts = [
        'pharmacie_garde' => 'boolean',
        'statut' => 'boolean',
        'horaire_ouverture' => 'datetime:H:i',
        'horaire_fermeture' => 'datetime:H:i',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * ============================================
     * MÉTHODES UTILES
     * ============================================
     */

    /**
     * Obtenir l'URL de la photo
     */
    public function photoUrl(): string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        
        // Photo par défaut d'une pharmacie
        return asset('images/default-pharmacie.png');
    }

    /**
     * Vérifier si la pharmacie a des coordonnées GPS
     */
    public function hasCoordinates(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    /**
     * Calculer la distance entre cette pharmacie et des coordonnées GPS
     * 
     * Formule de Haversine pour calculer la distance entre 2 points GPS
     * Retourne la distance en kilomètres
     * 
     * @param float $lat Latitude du point de référence
     * @param float $lon Longitude du point de référence
     * @return float Distance en km
     */
    public function calculerDistance(float $lat, float $lon): float
    {
        // Vérifier que cette pharmacie a des coordonnées
        if (!$this->hasCoordinates()) {
            return 999999; // Distance infinie si pas de coordonnées
        }

        // Rayon de la Terre en km
        $rayonTerre = 6371;

        // Convertir les degrés en radians
        $latFrom = deg2rad($lat);
        $lonFrom = deg2rad($lon);
        $latTo = deg2rad($this->latitude);
        $lonTo = deg2rad($this->longitude);

        // Différences
        $deltaLat = $latTo - $latFrom;
        $deltaLon = $lonTo - $lonFrom;

        // Formule de Haversine
        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($latFrom) * cos($latTo) *
             sin($deltaLon / 2) * sin($deltaLon / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Distance en kilomètres
        $distance = $rayonTerre * $c;

        return round($distance, 2); // Arrondir à 2 décimales
    }

    /**
     * Vérifier si la pharmacie est ouverte maintenant
     * 
     * @return bool
     */
    public function estOuverte(): bool
    {
        // Si pas d'horaires définis, on considère fermée
        if (!$this->horaire_ouverture || !$this->horaire_fermeture) {
            return false;
        }

        $heureActuelle = now()->format('H:i');
        $ouverture = $this->horaire_ouverture->format('H:i');
        $fermeture = $this->horaire_fermeture->format('H:i');

        return $heureActuelle >= $ouverture && $heureActuelle <= $fermeture;
    }

    /**
     * Obtenir le statut d'ouverture en texte
     * 
     * @return string
     */
    public function statutOuverture(): string
    {
        if ($this->estOuverte()) {
            return 'Ouverte';
        }

        if ($this->pharmacie_garde) {
            return 'De garde';
        }

        return 'Fermée';
    }

    /**
     * Obtenir les horaires formatés
     * 
     * @return string
     */
    public function horairesFormats(): string
    {
        if (!$this->horaire_ouverture || !$this->horaire_fermeture) {
            return 'Horaires non renseignés';
        }

        return $this->horaire_ouverture->format('H\hi') . ' - ' . 
               $this->horaire_fermeture->format('H\hi');
    }

    /**
     * Obtenir le lien Google Maps
     * 
     * @return string
     */
    public function lienGoogleMaps(): string
    {
        if (!$this->hasCoordinates()) {
            return '#';
        }

        return "https://www.google.com/maps/search/?api=1&query={$this->latitude},{$this->longitude}";
    }

    /**
     * Scope : Pharmacies actives uniquement
     */
    public function scopeActives($query)
    {
        return $query->where('statut', true);
    }

    /**
     * Scope : Pharmacies de garde
     */
    public function scopeDeGarde($query)
    {
        return $query->where('pharmacie_garde', true);
    }

    /**
     * Scope : Pharmacies avec coordonnées GPS
     */
    public function scopeAvecCoordonnees($query)
    {
        return $query->whereNotNull('latitude')
                    ->whereNotNull('longitude');
    }
}