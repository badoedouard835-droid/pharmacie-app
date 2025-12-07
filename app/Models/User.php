<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Les attributs qui peuvent être assignés en masse
     * Ces champs peuvent être remplis via User::create() ou $user->update()
     */
    protected $fillable = [
        'nom',
        'prenom',
        'name',
        'email',
        'password',
        'telephone',
        'adresse',
        'photo',
        'role',
        'statut',
    ];

    /**
     * Les attributs qui doivent être cachés lors de la sérialisation
     * (dans les réponses JSON par exemple)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être castés en types natifs PHP
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'statut' => 'boolean',
        ];
    }

    /**
     * ============================================
     * ACCESSEURS (Getters personnalisés)
     * ============================================
     */

    /**
     * Retourne l'URL complète de la photo de profil
     * Si pas de photo, retourne un avatar par défaut
     */
    public function photoUrl(): string
    {
        if ($this->photo) {
            // Si la photo existe, retourner l'URL complète
            return Storage::url($this->photo);
        }
        
        // Sinon, retourner un avatar par défaut (via UI Avatars)
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Retourne les initiales de l'utilisateur
     * Exemple : "Jean Dupont" → "JD"
     */
    public function initiales(): string
    {
        $words = explode(' ', $this->name);
        
        if (count($words) >= 2) {
            // Si au moins 2 mots : prendre première lettre de chaque
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        
        // Sinon, prendre les 2 premières lettres du nom
        return strtoupper(substr($this->name, 0, 2));
    }

    /**
     * Vérifie si l'utilisateur est administrateur
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifie si l'utilisateur est pharmacien
     */
    public function isPharmacien(): bool
    {
        return $this->role === 'pharmacien';
    }

    /**
     * Vérifie si l'utilisateur est vendeur
     */
    public function isVendeur(): bool
    {
        return $this->role === 'vendeur';
    }

    /**
     * ============================================
     * RELATIONS
     * ============================================
     */

    /**
     * Relation : Un utilisateur peut effectuer plusieurs ventes
     */
    public function ventes()
    {
        return $this->hasMany(\App\Models\Vente::class);
    }
}