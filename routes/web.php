<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\PharmacieController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Page d'accueil
|--------------------------------------------------------------------------
| Redirection vers login si non connecté, sinon vers dashboard
*/
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Routes d'authentification
|--------------------------------------------------------------------------
*/

// Formulaire de login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Traitement du login
Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors(['email' => 'Identifiants invalides']);
});

// Formulaire d'inscription
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Traitement du formulaire d'inscription
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
    ]);

    $data = $request->only('name', 'email', 'password');
    $data['password'] = bcrypt($data['password']);
    $data['role'] = 'vendeur'; // Rôle par défaut
    User::create($data);

    return redirect()->route('login')->with('success', 'Inscription réussie! Veuillez vous connecter.');
});

// Déconnexion
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Routes protégées par authentification
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ===============================
    // ROUTES DU PROFIL UTILISATEUR
    // ===============================
    Route::prefix('profil')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('upload-photo');
        Route::delete('/delete-photo', [ProfileController::class, 'deletePhoto'])->name('delete-photo');
        Route::put('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        Route::delete('/delete-account', [ProfileController::class, 'deleteAccount'])->name('delete-account');
    });

    // ===============================
    // ROUTES DES RESSOURCES PRINCIPALES
    // ===============================
    Route::resource('categories', CategorieController::class)->parameters([
        'categories' => 'categorie'
    ]);
    Route::resource('produits', ProduitController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('ventes', VenteController::class)->except(['edit', 'update', 'destroy']);
    
    // Routes additionnelles pour les ventes
    Route::prefix('ventes')->name('ventes.')->group(function () {
        Route::get('/{vente}/edit', [VenteController::class, 'edit'])->name('edit');
        Route::put('/{vente}', [VenteController::class, 'update'])->name('update');
        Route::put('/{vente}/annuler', [VenteController::class, 'annuler'])->name('annuler');
        Route::get('/{vente}/facture', [VenteController::class, 'facture'])->name('facture');
        Route::get('/{vente}/facture/preview', [VenteController::class, 'facturePreview'])->name('facture.preview');
    });

    // ===============================
    // ROUTES PHARMACIES
    // ===============================
    Route::resource('pharmacies', PharmacieController::class)->parameters([
        'pharmacies' => 'pharmacie'
    ]);
    Route::get('/pharmacies-proximite', [PharmacieController::class, 'proximite'])->name('pharmacies.proximite');

    // ============================================
    // ROUTES ADMIN - Gestion et supervision
    // ============================================
    Route::prefix('admin')->name('admin.')->group(function () {
        
        // Gestion des utilisateurs
        Route::get('/utilisateurs', [AdminController::class, 'utilisateurs'])->name('utilisateurs');
        Route::get('/utilisateurs/{user}', [AdminController::class, 'voirUtilisateur'])->name('utilisateur.profil');
        Route::put('/utilisateurs/{user}/role', [AdminController::class, 'changerRole'])->name('utilisateur.role');
        Route::delete('/utilisateurs/{user}/supprimer', [AdminController::class, 'supprimerUtilisateur'])->name('utilisateur.supprimer');
        Route::put('/utilisateurs/{user}/toggle', [AdminController::class, 'toggleStatut'])->name('utilisateur.toggle');
        
        // Alertes aux vendeurs
        Route::post('/alertes/envoyer', [AdminController::class, 'envoyerAlerte'])->name('envoyer-alerte');
        
        // Activités et rapports
        Route::get('/activites', [AdminController::class, 'activites'])->name('activites');
        Route::get('/rapports', [AdminController::class, 'rapports'])->name('rapports');
    });

});