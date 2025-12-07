<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Afficher la liste des catégories
     */
    public function index()
    {
        $categories = Categorie::withCount('produits')
                               ->orderBy('nom')
                               ->paginate(15);
        
        return view('categories.index', compact('categories'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Enregistrer une nouvelle catégorie
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:categories,nom'],
            'description' => ['nullable', 'string'],
        ], [
            'nom.required' => 'Le nom de la catégorie est obligatoire',
            'nom.unique' => 'Cette catégorie existe déjà',
        ]);

        Categorie::create($validated);

        return redirect()->route('categories.index')
                        ->with('success', 'Catégorie créée avec succès !');
    }

    /**
     * Afficher une catégorie
     */
    public function show(Categorie $categorie)
    {
        $produits = $categorie->produits()->paginate(20);
        
        return view('categories.show', compact('categorie', 'produits'));
    }

    /**
     * Afficher le formulaire de modification
     */
   public function edit(Categorie $categorie)
   {
    return view('categories.edit', compact('categorie'));
   }


    /**
     * Mettre à jour une catégorie
     */
    public function update(Request $request, Categorie $categorie)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:categories,nom,' . $categorie->id],
            'description' => ['nullable', 'string'],
        ], [
            'nom.required' => 'Le nom de la catégorie est obligatoire',
            'nom.unique' => 'Cette catégorie existe déjà',
        ]);

        $categorie->update($validated);

        return redirect()->route('categories.index')
                        ->with('success', 'Catégorie modifiée avec succès !');
    }

    /**
     * Supprimer une catégorie
     */
    public function destroy(Categorie $categorie)
    {
        // Vérifier si la catégorie contient des produits
        if ($categorie->produits()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer cette catégorie car elle contient des produits.');
        }

        $categorie->delete();

        return redirect()->route('categories.index')
                        ->with('success', 'Catégorie supprimée avec succès !');
    }
}