<?php

namespace App\Http\Controllers;

use App\Models\Pharmacie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PharmacieController extends Controller
{
    /**
     * ============================================
     * AFFICHER LA CARTE DES PHARMACIES
     * ============================================
     * Route : GET /pharmacies
     * 
     * Affiche une carte interactive avec toutes les pharmacies
     */
    public function index(Request $request)
    {
        // Récupérer toutes les pharmacies actives avec coordonnées GPS
        $pharmacies = Pharmacie::actives()
            ->avecCoordonnees()
            ->get();

        // Pharmacies de garde
        $pharmaciesGarde = Pharmacie::actives()
            ->deGarde()
            ->avecCoordonnees()
            ->get();

        // Statistiques
        $stats = [
            'total' => Pharmacie::actives()->count(),
            'avec_gps' => Pharmacie::actives()->avecCoordonnees()->count(),
            'de_garde' => Pharmacie::actives()->deGarde()->count(),
        ];

        return view('pharmacies.index', compact('pharmacies', 'pharmaciesGarde', 'stats'));
    }

    /**
     * ============================================
     * AFFICHER LE FORMULAIRE DE CRÉATION
     * ============================================
     * Route : GET /pharmacies/create
     */
    public function create()
    {
        return view('pharmacies.create');
    }

    /**
     * ============================================
     * ENREGISTRER UNE NOUVELLE PHARMACIE
     * ============================================
     * Route : POST /pharmacies
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'adresse' => ['required', 'string'],
            'ville' => ['required', 'string', 'max:255'],
            'quartier' => ['nullable', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'horaire_ouverture' => ['nullable', 'date_format:H:i'],
            'horaire_fermeture' => ['nullable', 'date_format:H:i'],
            'jours_ouverture' => ['nullable', 'string', 'max:255'],
            'pharmacie_garde' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        // Upload de la photo
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('pharmacies', 'public');
        }

        // Créer la pharmacie
        $pharmacie = Pharmacie::create($validated);

        return redirect()->route('pharmacies.show', $pharmacie)
            ->with('success', 'Pharmacie ajoutée avec succès !');
    }

    /**
     * ============================================
     * AFFICHER LES DÉTAILS D'UNE PHARMACIE
     * ============================================
     * Route : GET /pharmacies/{id}
     */
    public function show(Pharmacie $pharmacie)
    {
        return view('pharmacies.show', compact('pharmacie'));
    }

    /**
     * ============================================
     * AFFICHER LE FORMULAIRE DE MODIFICATION
     * ============================================
     * Route : GET /pharmacies/{id}/edit
     */
    public function edit(Pharmacie $pharmacie)
    {
        return view('pharmacies.edit', compact('pharmacie'));
    }

    /**
     * ============================================
     * METTRE À JOUR UNE PHARMACIE
     * ============================================
     * Route : PUT /pharmacies/{id}
     */
    public function update(Request $request, Pharmacie $pharmacie)
    {
        // Validation
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'adresse' => ['required', 'string'],
            'ville' => ['required', 'string', 'max:255'],
            'quartier' => ['nullable', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'horaire_ouverture' => ['nullable', 'date_format:H:i'],
            'horaire_fermeture' => ['nullable', 'date_format:H:i'],
            'jours_ouverture' => ['nullable', 'string', 'max:255'],
            'pharmacie_garde' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        // Upload de la photo
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo
            if ($pharmacie->photo) {
                Storage::delete('public/' . $pharmacie->photo);
            }
            
            $validated['photo'] = $request->file('photo')->store('pharmacies', 'public');
        }

        // Mettre à jour
        $pharmacie->update($validated);

        return redirect()->route('pharmacies.show', $pharmacie)
            ->with('success', 'Pharmacie modifiée avec succès !');
    }

    /**
     * ============================================
     * SUPPRIMER UNE PHARMACIE
     * ============================================
     * Route : DELETE /pharmacies/{id}
     */
    public function destroy(Pharmacie $pharmacie)
    {
        // Supprimer la photo
        if ($pharmacie->photo) {
            Storage::delete('public/' . $pharmacie->photo);
        }

        $pharmacie->delete();

        return redirect()->route('pharmacies.index')
            ->with('success', 'Pharmacie supprimée avec succès.');
    }

    /**
     * ============================================
     * RECHERCHER DES PHARMACIES À PROXIMITÉ
     * ============================================
     * Route : GET /pharmacies/proximite
     * 
     * Recherche basée sur les coordonnées GPS de l'utilisateur
     */
    public function proximite(Request $request)
    {
        // Validation
        $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'rayon' => ['nullable', 'numeric', 'min:1', 'max:50'], // Rayon en km
        ]);

        $lat = $request->latitude;
        $lon = $request->longitude;
        $rayon = $request->rayon ?? 10; // 10 km par défaut

        // Récupérer toutes les pharmacies actives avec GPS
        $toutesPharmacies = Pharmacie::actives()
            ->avecCoordonnees()
            ->get();

        // Filtrer par distance
        $pharmaciesProches = $toutesPharmacies->filter(function ($pharmacie) use ($lat, $lon, $rayon) {
            $distance = $pharmacie->calculerDistance($lat, $lon);
            $pharmacie->distance = $distance; // Ajouter la distance à l'objet
            return $distance <= $rayon;
        })->sortBy('distance'); // Trier par distance croissante

        return response()->json([
            'success' => true,
            'pharmacies' => $pharmaciesProches->values(),
            'total' => $pharmaciesProches->count(),
        ]);
    }
}