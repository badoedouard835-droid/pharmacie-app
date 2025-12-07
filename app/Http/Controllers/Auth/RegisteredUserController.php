<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * ============================================
     * AFFICHER LE FORMULAIRE D'INSCRIPTION
     * ============================================
     * Route : GET /register
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * ============================================
     * TRAITER L'INSCRIPTION (Soumettre le formulaire)
     * ============================================
     * Route : POST /register
     * 
     * Cette fonction :
     * 1. Valide les données du formulaire
     * 2. Crée l'utilisateur dans la base de données
     * 3. Connecte automatiquement l'utilisateur
     * 4. Le redirige vers le dashboard
     */
    public function store(Request $request): RedirectResponse
    {
        // ============================================
        // ÉTAPE 1 : VALIDATION DES DONNÉES
        // ============================================
        $request->validate([
            // Nom : obligatoire, texte, max 255 caractères
            'name' => ['required', 'string', 'max:255'],
            
            // Email : obligatoire, format email, unique dans la table users
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            
            // ✅ Téléphone : optionnel (nullable), max 20 caractères
            'telephone' => ['nullable', 'string', 'max:20'],
            
            // ✅ Adresse : optionnelle
            'adresse' => ['nullable', 'string'],
            
            // Mot de passe : obligatoire, confirmé (doit correspondre à password_confirmation)
            // Rules\Password::defaults() = minimum 8 caractères
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // ============================================
        // ÉTAPE 2 : CRÉER L'UTILISATEUR
        // ============================================
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            
            // ✅ Champs personnalisés
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            
            // Hash::make() crypte le mot de passe
            // Note : avec le cast 'hashed', on peut mettre directement le password
            'password' => Hash::make($request->password),
            
            // ✅ Rôle par défaut : vendeur
            // L'admin devra changer le rôle manuellement si besoin
            'role' => 'vendeur',
            
            // ✅ Statut actif par défaut
            'statut' => true,
        ]);

        // ============================================
        // ÉTAPE 3 : DÉCLENCHER L'ÉVÉNEMENT "REGISTERED"
        // ============================================
        // Permet d'envoyer un email de vérification si configuré
        event(new Registered($user));

        // ============================================
        // ÉTAPE 4 : CONNECTER L'UTILISATEUR AUTOMATIQUEMENT
        // ============================================
        Auth::login($user);

        // ============================================
        // ÉTAPE 5 : REDIRIGER VERS LE DASHBOARD
        // ============================================
        return redirect(route('dashboard', absolute: false));
    }
}