<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\LigneVente;
use App\Models\Client;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class VenteController extends Controller
{
    /**
     * Afficher la liste des ventes (avec filtres et stats)
     * Route: GET /ventes
     */
    public function index(Request $request)
    {
        $query = Vente::with(['client', 'user'])
            ->orderBy('date_vente', 'desc');

        // Recherche par numéro
        if ($request->filled('search')) {
            $query->where('numero_vente', 'LIKE', "%{$request->search}%");
        }

        // Filtre statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtre mode paiement
        if ($request->filled('mode_paiement')) {
            $query->where('mode_paiement', $request->mode_paiement);
        }

        // Filtre dates
        if ($request->filled('date_debut')) {
            $query->whereDate('date_vente', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_vente', '<=', $request->date_fin);
        }

        $ventes = $query->paginate(15)->withQueryString();

        $stats = [
            'total_ventes' => Vente::where('statut', 'validee')->count(),
            'total_ca' => Vente::where('statut', 'validee')->sum('montant_total'),
            'ventes_jour' => Vente::where('statut', 'validee')->whereDate('date_vente', today())->count(),
            'ca_jour' => Vente::where('statut', 'validee')->whereDate('date_vente', today())->sum('montant_total'),
        ];

        return view('ventes.index', compact('ventes', 'stats'));
    }

    /**
     * Afficher le formulaire de création
     * Route: GET /ventes/create
     */
    public function create()
    {
        // 🔥🔥🔥 ÉTAPE 2 APPLIQUÉE ICI
        $client = null;
        if (request()->has('client_id')) {
            $client = Client::find(request('client_id'));
        }
        // 🔥🔥🔥 FIN AJOUT

        $numeroVente = Vente::genererNumero();
        $clients = Client::orderBy('nom')->get();
        $produits = Produit::where('statut', true)
            ->where('quantite_stock', '>', 0)
            ->orderBy('nom')
            ->get();

        // 🔥 Ajout du $client dans la vue (aucune ligne supprimée)
        return view('ventes.create', compact('numeroVente', 'clients', 'produits', 'client'));
    }

    /**
     * Enregistrer une nouvelle vente (Option A: date automatique)
     * Route: POST /ventes
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'client_id' => ['nullable', 'exists:clients,id'],
            // date_vente non requise car option A (automatique)
            'mode_paiement' => ['required', 'in:especes,carte,mobile_money,cheque'],
            'remise' => ['nullable', 'numeric', 'min:0'],
            'remarques' => ['nullable', 'string'],
            'produits' => ['required', 'array', 'min:1'],
            'produits.*.produit_id' => ['required', 'exists:produits,id'],
            'produits.*.quantite' => ['required', 'integer', 'min:1'],
            'produits.*.prix_unitaire' => ['required', 'numeric', 'min:0'],
            'produits.*.remise' => ['nullable', 'numeric', 'min:0'],
        ], [
            'produits.required' => 'Veuillez ajouter au moins un produit',
            'produits.min' => 'Veuillez ajouter au moins un produit',
            'produits.*.produit_id.required' => 'Produit invalide',
            'produits.*.produit_id.exists' => 'Ce produit n\'existe pas',
            'produits.*.quantite.required' => 'Quantité requise',
            'produits.*.quantite.min' => 'La quantité doit être au moins 1',
            'mode_paiement.required' => 'Le mode de paiement est obligatoire',
        ]);

        // Vérification initiale des stocks (préventive)
        foreach ($request->produits as $item) {
            $produit = Produit::find($item['produit_id']);
            if (!$produit) {
                return back()->with('error', 'Produit introuvable')->withInput();
            }
            if ($produit->quantite_stock < $item['quantite']) {
                return back()->with('error', "Stock insuffisant pour {$produit->nom}. Stock disponible : {$produit->quantite_stock}")->withInput();
            }
        }

        // Transaction : création vente, lignes, et mise à jour stock
        try {
            DB::beginTransaction();

            // 1) Créer la vente (date automatique)
            $vente = Vente::create([
                'numero_vente' => Vente::genererNumero(),
                'client_id' => $validated['client_id'] ?? null,
                'user_id' => Auth::id(),
                'date_vente' => now(),
                'montant_total' => 0, // calculé ensuite
                'montant_ht' => 0,
                'montant_tva' => 0,
                'remise' => $validated['remise'] ?? 0,
                'mode_paiement' => $validated['mode_paiement'],
                'statut' => 'validee',
                'remarques' => $validated['remarques'] ?? null,
            ]);

            // 2) Construire et enregistrer les lignes, mettre à jour les stocks
            $montantTotal = 0;
            $lignesData = [];

            foreach ($request->produits as $produitData) {
                $produit = Produit::lockForUpdate()->findOrFail($produitData['produit_id']); // lock pour éviter concurrence
                $quantite = (int) $produitData['quantite'];
                $prixUnitaire = (float) $produitData['prix_unitaire'];
                $remiseLigne = isset($produitData['remise']) ? (float) $produitData['remise'] : 0.0;

                if ($produit->quantite_stock < $quantite) {
                    throw new \Exception("Stock insuffisant pour {$produit->nom}");
                }

                $montantLigne = ($prixUnitaire * $quantite) - $remiseLigne;
                $montantTotal += $montantLigne;

                $lignesData[] = [
                    'vente_id' => $vente->id,
                    'produit_id' => $produit->id,
                    'quantite' => $quantite,
                    'prix_unitaire' => $prixUnitaire,
                    'remise' => $remiseLigne,
                    'montant_total' => $montantLigne,
                ];

                // Décrémenter le stock (ici, contrôlé explicitement)
                $produit->decrement('quantite_stock', $quantite);
            }

            // 3) Appliquer remise globale, calculer HT et TVA
            $remiseGlobale = $validated['remise'] ?? 0;
            $montantApresRemise = $montantTotal - $remiseGlobale;

            // TVA (18%)
            $tauxTVA = 0.18;
            $montantHT = $montantApresRemise / (1 + $tauxTVA);
            $montantTVA = $montantApresRemise - $montantHT;

            // 4) Enregistrer les lignes de vente sans déclencher d'événements modèles (pour éviter double-modifs stock)
            LigneVente::withoutEvents(function () use ($lignesData) {
                foreach ($lignesData as $ld) {
                    LigneVente::create($ld);
                }
            });

            // 5) Mettre à jour la vente avec les montants finaux
            $vente->update([
                'montant_total' => $montantApresRemise,
                'montant_ht' => $montantHT,
                'montant_tva' => $montantTVA,
            ]);

            DB::commit();

            return redirect()->route('ventes.show', $vente)->with('success', 'Vente enregistrée avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Erreur : ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Afficher les détails d'une vente
     * Route: GET /ventes/{vente}
     */
    public function show(Vente $vente)
    {
        // Remplacement de 'lignes' par 'lignesVentes' pour correspondre au modèle
        $vente->load(['client', 'user', 'lignesVentes.produit']);
        return view('ventes.show', compact('vente'));
    }

    /**
     * Annuler une vente et restaurer les stocks
     * Route: PUT /ventes/{vente}/annuler
     */
    public function annuler(Vente $vente)
    {
        if ($vente->statut === 'annulee') {
            return back()->with('error', 'Cette vente est déjà annulée.');
        }

        try {
            DB::beginTransaction();

            // Remettre les produits en stock (verrouillage pour sécurité)
            foreach ($vente->lignesVentes as $ligne) {
                $produit = Produit::lockForUpdate()->find($ligne->produit_id);
                if ($produit) {
                    $produit->increment('quantite_stock', $ligne->quantite);
                }
            }

            $vente->update(['statut' => 'annulee']);

            DB::commit();

            return redirect()->route('ventes.index')->with('success', 'Vente annulée avec succès. Les stocks ont été restaurés.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de l\'annulation : ' . $e->getMessage());
        }
    }

    /**
     * Générer la facture PDF
     * Route: GET /ventes/{vente}/facture
     */
    public function facture(Vente $vente)
    {
        $vente->load(['client', 'user', 'lignesVentes.produit']);
        $pdf = PDF::loadView('ventes.facture', compact('vente'));
        return $pdf->download('Facture_' . $vente->numero_vente . '.pdf');
    }

    /**
     * Afficher la facture avant téléchargement (preview)
     * Route: GET /ventes/{vente}/facture/preview
     */
    public function facturePreview(Vente $vente)
    {
        $vente->load(['client', 'user', 'lignesVentes.produit']);
        return view('ventes.facture', compact('vente'));
    }
}
