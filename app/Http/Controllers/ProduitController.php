<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    /**
     * Afficher la liste des produits
     */
    public function index(Request $request)
    {
        $query = Produit::with('categorie');

        // Recherche
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtre par catégorie
        if ($request->filled('categorie')) {
            $query->where('categorie_id', $request->categorie);
        }

        // Filtre par statut stock
        if ($request->filled('stock')) {
            switch ($request->stock) {
                case 'rupture':
                    $query->rupture();
                    break;
                case 'faible':
                    $query->stockFaible();
                    break;
                case 'ok':
                    $query->enStock();
                    break;
            }
        }

        // Filtre par expiration
        if ($request->filled('expiration')) {
            switch ($request->expiration) {
                case 'perime':
                    $query->perime();
                    break;
                case 'proche':
                    $query->prochePeriemption();
                    break;
            }
        }

        $produits = $query->orderBy('nom')->paginate(20);
        $categories = Categorie::orderBy('nom')->get();

        return view('produits.index', compact('produits', 'categories'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $categories = Categorie::orderBy('nom')->get();
        return view('produits.create', compact('categories'));
    }

    /**
     * Enregistrer un nouveau produit
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_barre' => ['nullable', 'string', 'unique:produits,code_barre'],
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'categorie_id' => ['required', 'exists:categories,id'],
            'prix_achat' => ['required', 'numeric', 'min:0'],
            'prix_vente' => ['required', 'numeric', 'min:0'],
            'quantite_stock' => ['required', 'integer', 'min:0'],
            'stock_minimum' => ['required', 'integer', 'min:0'],
            'date_expiration' => ['nullable', 'date', 'after:today'],
            'laboratoire' => ['nullable', 'string', 'max:255'],
            'forme' => ['nullable', 'string', 'max:100'],
            'dosage' => ['nullable', 'string', 'max:100'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'statut' => ['boolean'],
        ], [
            'nom.required' => 'Le nom du produit est obligatoire',
            'categorie_id.required' => 'Veuillez sélectionner une catégorie',
            'prix_achat.required' => 'Le prix d\'achat est obligatoire',
            'prix_vente.required' => 'Le prix de vente est obligatoire',
            'quantite_stock.required' => 'La quantité en stock est obligatoire',
            'date_expiration.after' => 'La date d\'expiration doit être dans le futur',
        ]);

        // Upload de la photo
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('produits', 'public');
        }

        Produit::create($validated);

        return redirect()->route('produits.index')
                        ->with('success', 'Produit créé avec succès !');
    }

    /**
     * Afficher un produit
     */
    public function show(Produit $produit)
    {
        return view('produits.show', compact('produit'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Produit $produit)
    {
        $categories = Categorie::orderBy('nom')->get();
        return view('produits.edit', compact('produit', 'categories'));
    }

    /**
     * Mettre à jour un produit
     */
    public function update(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'code_barre' => ['nullable', 'string', 'unique:produits,code_barre,' . $produit->id],
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'categorie_id' => ['required', 'exists:categories,id'],
            'prix_achat' => ['required', 'numeric', 'min:0'],
            'prix_vente' => ['required', 'numeric', 'min:0'],
            'quantite_stock' => ['required', 'integer', 'min:0'],
            'stock_minimum' => ['required', 'integer', 'min:0'],
            'date_expiration' => ['nullable', 'date'],
            'laboratoire' => ['nullable', 'string', 'max:255'],
            'forme' => ['nullable', 'string', 'max:100'],
            'dosage' => ['nullable', 'string', 'max:100'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'statut' => ['boolean'],
        ]);

        // Upload de la nouvelle photo
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo
            if ($produit->photo) {
                Storage::delete('public/' . $produit->photo);
            }
            $validated['photo'] = $request->file('photo')->store('produits', 'public');
        }

        $produit->update($validated);

        return redirect()->route('produits.index')
                        ->with('success', 'Produit modifié avec succès !');
    }

    /**
     * Supprimer un produit
     */
    public function destroy(Produit $produit)
    {
        // Supprimer la photo
        if ($produit->photo) {
            Storage::delete('public/' . $produit->photo);
        }

        $produit->delete();

        return redirect()->route('produits.index')
                        ->with('success', 'Produit supprimé avec succès !');
    }
}