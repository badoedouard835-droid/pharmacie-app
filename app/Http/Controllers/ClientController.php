<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * ============================================
     * AFFICHER LA LISTE DES CLIENTS
     * ============================================
     * Route : GET /clients
     * 
     * Cette fonction :
     * - Récupère tous les clients
     * - Applique les filtres de recherche
     * - Pagine les résultats
     */
    public function index(Request $request)
    {
        // Construire la requête de base
        $query = Client::query();

        // ============================================
        // FILTRES DE RECHERCHE
        // ============================================
        
        // Recherche par nom/prénom/téléphone
        if ($request->filled('search')) {
            $search = $request->search;
            
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('telephone', 'LIKE', "%{$search}%")
                  ->orWhere('numero_client', 'LIKE', "%{$search}%");
            });
        }

        // Filtre par type (particulier / professionnel)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtre par ville
        if ($request->filled('ville')) {
            $query->where('ville', 'LIKE', "%{$request->ville}%");
        }

        // ============================================
        // TRI
        // ============================================
        // Par défaut : les plus récents en premier
        $query->orderBy('created_at', 'desc');

        // Pagination : 15 clients par page
        $clients = $query->paginate(15)->withQueryString();
        // withQueryString() : garde les paramètres de recherche dans la pagination

        // ============================================
        // STATISTIQUES
        // ============================================
        $stats = [
            'total' => Client::count(),
            'particuliers' => Client::where('type', 'particulier')->count(),
            'professionnels' => Client::where('type', 'professionnel')->count(),
            'nouveaux_mois' => Client::whereMonth('created_at', now()->month)->count(),
        ];

        return view('clients.index', compact('clients', 'stats'));
    }

    /**
     * ============================================
     * AFFICHER LE FORMULAIRE DE CRÉATION
     * ============================================
     * Route : GET /clients/create
     */
    public function create()
    {
        // Générer un numéro de client automatiquement
        $numeroClient = Client::genererNumero();
        
        return view('clients.create', compact('numeroClient'));
    }

    /**
     * ============================================
     * ENREGISTRER UN NOUVEAU CLIENT
     * ============================================
     * Route : POST /clients
     */
    public function store(Request $request)
    {
        // ============================================
        // VALIDATION DES DONNÉES
        // ============================================
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:20', 'unique:clients'],
            // unique:clients : vérifie que le téléphone n'existe pas déjà
            
            'email' => ['nullable', 'email', 'max:255'],
            'adresse' => ['nullable', 'string'],
            'ville' => ['nullable', 'string', 'max:255'],
            'date_naissance' => ['nullable', 'date', 'before:today'],
            // before:today : la date doit être dans le passé
            
            'sexe' => ['nullable', 'in:M,F'],
            // in:M,F : doit être soit M soit F
            
            'type' => ['required', 'in:particulier,professionnel'],
            'remarques' => ['nullable', 'string'],
        ], [
            // Messages d'erreur personnalisés
            'nom.required' => 'Le nom est obligatoire',
            'prenom.required' => 'Le prénom est obligatoire',
            'telephone.required' => 'Le téléphone est obligatoire',
            'telephone.unique' => 'Ce numéro de téléphone existe déjà',
            'email.email' => 'Format d\'email invalide',
            'date_naissance.before' => 'La date de naissance doit être dans le passé',
            'type.required' => 'Le type de client est obligatoire',
        ]);

        // ============================================
        // GÉNÉRER LE NUMÉRO CLIENT
        // ============================================
        $validated['numero_client'] = Client::genererNumero();

        // ============================================
        // CRÉER LE CLIENT
        // ============================================
        Client::create($validated);

        // Redirection avec message de succès
        return redirect()->route('clients.index')
            ->with('success', 'Le client a été ajouté avec succès !');
    }

    /**
     * ============================================
     * AFFICHER LA FICHE DÉTAILLÉE D'UN CLIENT
     * ============================================
     * Route : GET /clients/{id}
     * 
     * Affiche toutes les informations du client
     * + son historique d'achats
     */
    public function show(Client $client)
    {
        // Charger les ventes du client avec les détails
        // with() : chargement eager (évite N+1 queries)
        $ventes = $client->ventes()
            ->with('user') // Récupère aussi le vendeur
            ->orderBy('date_vente', 'desc')
            ->get();

        // Calculer les statistiques du client
        $stats = [
            'total_achats' => $client->totalAchats(),
            'nombre_achats' => $client->nombreAchats(),
            'dernier_achat' => $client->dernierAchat(),
            'panier_moyen' => $client->nombreAchats() > 0 
                ? $client->totalAchats() / $client->nombreAchats() 
                : 0,
        ];

        return view('clients.show', compact('client', 'ventes', 'stats'));
    }

    /**
     * ============================================
     * AFFICHER LE FORMULAIRE DE MODIFICATION
     * ============================================
     * Route : GET /clients/{id}/edit
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * ============================================
     * METTRE À JOUR UN CLIENT
     * ============================================
     * Route : PUT /clients/{id}
     */
    public function update(Request $request, Client $client)
    {
        // Validation (même chose que store, sauf pour le téléphone)
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            
            // Téléphone unique SAUF pour ce client
            'telephone' => ['required', 'string', 'max:20', 'unique:clients,telephone,' . $client->id],
            
            'email' => ['nullable', 'email', 'max:255'],
            'adresse' => ['nullable', 'string'],
            'ville' => ['nullable', 'string', 'max:255'],
            'date_naissance' => ['nullable', 'date', 'before:today'],
            'sexe' => ['nullable', 'in:M,F'],
            'type' => ['required', 'in:particulier,professionnel'],
            'remarques' => ['nullable', 'string'],
        ]);

        // Mettre à jour le client
        $client->update($validated);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Le client a été modifié avec succès !');
    }

    /**
     * ============================================
     * SUPPRIMER UN CLIENT
     * ============================================
     * Route : DELETE /clients/{id}
     */
    public function destroy(Client $client)
    {
        // Vérifier si le client a des ventes
        if ($client->ventes()->count() > 0) {
            return redirect()->route('clients.index')
                ->with('error', 'Impossible de supprimer ce client car il a des ventes enregistrées.');
        }

        // Supprimer le client
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Le client a été supprimé avec succès.');
    }
}