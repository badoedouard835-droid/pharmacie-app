<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vente;
use App\Models\Produit;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Vérifier que l'utilisateur est admin avant chaque méthode
     */
    private function verifierAdmin()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent accéder à cette section.');
        }
    }

    /**
     * Liste des utilisateurs avec filtres et recherche
     */
    public function utilisateurs(Request $request)
    {
        $this->verifierAdmin();

        $query = User::query();

        // Recherche par nom ou email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
        }

        // Filtre par rôle
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // Charger le nombre de ventes par utilisateur et paginer
        $utilisateurs = $query->withCount('ventes')
                             ->orderBy('created_at', 'desc')
                             ->paginate(15);

        // Statistiques rapides
        $stats = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'vendeurs' => User::where('role', 'vendeur')->count(),
            'pharmaciens' => User::where('role', 'pharmacien')->count(),
            'actifs_mois' => User::where('created_at', '>=', now()->subMonth())->count(),
        ];

        return view('admin.utilisateurs', compact('utilisateurs', 'stats'));
    }

    /**
     * Voir le profil détaillé d'un utilisateur
     */
    public function voirUtilisateur(User $user)
    {
        $this->verifierAdmin();

        // Charger les ventes de l'utilisateur
        $ventes = $user->ventes()
                      ->with(['client', 'lignesVentes'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);

        // Statistiques de l'utilisateur
        $stats = [
            'total_ventes' => $user->ventes()->count(),
            'ca_total' => $user->ventes()->sum('montant_total'),
            'ca_mois' => $user->ventes()
                             ->whereMonth('created_at', now()->month)
                             ->sum('montant_total'),
            'ventes_annulees' => $user->ventes()->where('statut', 'annulee')->count(),
            'membre_depuis' => $user->created_at->diffForHumans(),
        ];

        // Ventes par mois (6 derniers mois)
        $ventesParMois = $user->ventes()
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mois'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(montant_total) as ca')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mois')
            ->orderBy('mois', 'asc')
            ->get();

        return view('admin.utilisateur-profil', compact('user', 'ventes', 'stats', 'ventesParMois'));
    }

    /**
     * Changer le rôle d'un utilisateur
     * Accepte les requêtes PUT
     */
    public function changerRole(Request $request, User $user)
    {
        $this->verifierAdmin();

        // Validation du rôle
        $validated = $request->validate([
            'role' => 'required|in:admin,vendeur,pharmacien'
        ], [
            'role.required' => 'Le rôle est obligatoire',
            'role.in' => 'Le rôle sélectionné est invalide'
        ]);

        // Protection : ne pas pouvoir modifier son propre rôle
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas modifier votre propre rôle');
        }

        // Sauvegarder l'ancien rôle pour le log
        $oldRole = $user->role;
        
        // Mettre à jour le rôle
        $user->update(['role' => $validated['role']]);

        // Logger l'action pour les audits
        Log::info("Changement de rôle utilisateur", [
            'admin_id' => Auth::id(),
            'user_id' => $user->id,
            'user_name' => $user->name,
            'old_role' => $oldRole,
            'new_role' => $user->role,
            'timestamp' => now()
        ]);

        return back()->with('success', "Le rôle de {$user->name} a été changé de {$oldRole} à {$user->role} avec succès.");
    }

    /**
     * Supprimer un utilisateur avec vérification du mot de passe
     */
    public function supprimerUtilisateur(Request $request, User $user)
    {
        $this->verifierAdmin();

        // Protection : ne pas pouvoir supprimer soi-même
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte');
        }

        // Validation
        $request->validate([
            'password' => 'required',
            'confirm' => 'required|accepted'
        ], [
            'password.required' => 'Le mot de passe est obligatoire',
            'confirm.required' => 'Vous devez confirmer la suppression',
            'confirm.accepted' => 'Vous devez confirmer la suppression'
        ]);

        // Vérifier le mot de passe de l'admin
        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->withErrors(['password' => 'Le mot de passe est incorrect']);
        }

        // Sauvegarder les infos avant suppression
        $userName = $user->name;
        $userEmail = $user->email;

        // Supprimer l'utilisateur
        $user->delete();

        // Logger l'action
        Log::warning("Suppression d'utilisateur", [
            'admin_id' => Auth::id(),
            'admin_name' => Auth::user()->name,
            'user_id' => $user->id,
            'user_name' => $userName,
            'user_email' => $userEmail,
            'timestamp' => now()
        ]);

        return back()->with('success', "L'utilisateur {$userName} a été supprimé avec succès.");
    }

    /**
     * Envoyer une alerte à des utilisateurs sélectionnés
     */
    public function envoyerAlerte(Request $request)
    {
        $this->verifierAdmin();

        // Validation
        $validated = $request->validate([
            'destinataires' => 'required|array|min:1',
            'destinataires.*' => 'required|string',
            'titre' => 'required|string|max:100',
            'message' => 'required|string|max:1000',
            'type' => 'required|in:info,success,warning,danger'
        ], [
            'destinataires.required' => 'Vous devez sélectionner au moins un destinataire',
            'destinataires.min' => 'Vous devez sélectionner au moins un destinataire',
            'titre.required' => 'Le titre est obligatoire',
            'titre.max' => 'Le titre ne doit pas dépasser 100 caractères',
            'message.required' => 'Le message est obligatoire',
            'message.max' => 'Le message ne doit pas dépasser 1000 caractères',
            'type.required' => 'Le type d\'alerte est obligatoire',
            'type.in' => 'Le type d\'alerte sélectionné est invalide'
        ]);

        // Récupérer les utilisateurs destinataires
        $utilisateurs = collect();

        foreach ($validated['destinataires'] as $destinataire) {
            if ($destinataire === 'tous_vendeurs') {
                $utilisateurs = $utilisateurs->merge(User::where('role', 'vendeur')->get());
            } elseif ($destinataire === 'tous_pharmaciens') {
                $utilisateurs = $utilisateurs->merge(User::where('role', 'pharmacien')->get());
            } elseif ($destinataire === 'tous_admins') {
                $utilisateurs = $utilisateurs->merge(User::where('role', 'admin')->get());
            } elseif ($destinataire === 'tous') {
                $utilisateurs = $utilisateurs->merge(User::all());
            } elseif (str_starts_with($destinataire, 'user_')) {
                $userId = str_replace('user_', '', $destinataire);
                $user = User::find($userId);
                if ($user) {
                    $utilisateurs->push($user);
                }
            }
        }

        // Supprimer les doublons
        $utilisateurs = $utilisateurs->unique('id');

        if ($utilisateurs->isEmpty()) {
            return back()->with('warning', 'Aucun utilisateur trouvé pour les destinataires sélectionnés.');
        }

        // Envoyer la notification à chaque utilisateur
        foreach ($utilisateurs as $utilisateur) {
            \Illuminate\Support\Facades\Notification::send($utilisateur, new \App\Notifications\AlerteVendeur(
                $validated['titre'],
                $validated['message'],
                $validated['type']
            ));
        }

        // Logger l'action
        Log::info("Notification envoyée", [
            'admin_id' => Auth::id(),
            'admin_name' => Auth::user()->name,
            'nombre_destinataires' => $utilisateurs->count(),
            'titre' => $validated['titre'],
            'type' => $validated['type'],
            'destinataires' => $utilisateurs->pluck('email')->join(', '),
            'timestamp' => now()
        ]);

        return back()->with('success', "La notification a été envoyée à {$utilisateurs->count()} utilisateur(s).");
    }

    /**
     * Activer/Désactiver un utilisateur
     */
    public function toggleStatut(User $user)
    {
        // Protection : ne pas pouvoir désactiver soi-même
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte');
        }

        // Inverser le statut
        $user->update(['statut' => !$user->statut]);

        $status = $user->statut ? 'activé' : 'désactivé';
        
        Log::info("Changement de statut utilisateur", [
            'admin_id' => Auth::id(),
            'user_id' => $user->id,
            'user_name' => $user->name,
            'nouveau_statut' => $user->statut
        ]);

        return back()->with('success', "L'utilisateur {$user->name} a été {$status} avec succès.");
    }

    /**
     * Activités et statistiques globales
     */
    public function activites(Request $request)
    {
        $this->verifierAdmin();

        $periode = $request->get('periode', 'mois'); // jour, semaine, mois, annee

        // Définir la date de début selon la période
        $dateDebut = match($periode) {
            'jour' => now()->startOfDay(),
            'semaine' => now()->startOfWeek(),
            'mois' => now()->startOfMonth(),
            'annee' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        // Statistiques générales
        $stats = [
            'ventes_total' => Vente::where('created_at', '>=', $dateDebut)->count(),
            'ca_total' => Vente::where('created_at', '>=', $dateDebut)->sum('montant_total'),
            'produits_vendus' => DB::table('lignes_ventes')
                ->join('ventes', 'lignes_ventes.vente_id', '=', 'ventes.id')
                ->where('ventes.created_at', '>=', $dateDebut)
                ->sum('lignes_ventes.quantite'),
            'nouveaux_clients' => Client::where('created_at', '>=', $dateDebut)->count(),
        ];

        // Top vendeurs
        $topVendeurs = User::select('users.*')
            ->selectRaw('COUNT(ventes.id) as total_ventes')
            ->selectRaw('SUM(ventes.montant_total) as ca_total')
            ->leftJoin('ventes', function($join) use ($dateDebut) {
                $join->on('users.id', '=', 'ventes.user_id')
                     ->where('ventes.created_at', '>=', $dateDebut);
            })
            ->groupBy('users.id')
            ->orderBy('ca_total', 'desc')
            ->limit(5)
            ->get();

        // Top produits vendus
        $topProduits = Produit::select('produits.*')
            ->selectRaw('SUM(lignes_ventes.quantite) as quantite_vendue')
            ->selectRaw('SUM(lignes_ventes.prix_total_ttc) as ca_produit')
            ->join('lignes_ventes', 'produits.id', '=', 'lignes_ventes.produit_id')
            ->join('ventes', 'lignes_ventes.vente_id', '=', 'ventes.id')
            ->where('ventes.created_at', '>=', $dateDebut)
            ->groupBy('produits.id')
            ->orderBy('quantite_vendue', 'desc')
            ->limit(10)
            ->get();

        // Ventes par jour (pour graphique)
        $ventesParJour = Vente::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(montant_total) as ca')
            )
            ->where('created_at', '>=', $dateDebut)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.activites', compact(
            'stats', 
            'topVendeurs', 
            'topProduits', 
            'ventesParJour',
            'periode'
        ));
    }

    /**
     * Rapports détaillés
     */
    public function rapports(Request $request)
    {
        $this->verifierAdmin();

        $type = $request->get('type', 'ventes'); // ventes, produits, clients
        $dateDebut = $request->get('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->get('date_fin', now()->format('Y-m-d'));

        $rapportData = [];

        switch($type) {
            case 'ventes':
                $rapportData = $this->rapportVentes($dateDebut, $dateFin);
                break;
            case 'produits':
                $rapportData = $this->rapportProduits($dateDebut, $dateFin);
                break;
            case 'clients':
                $rapportData = $this->rapportClients($dateDebut, $dateFin);
                break;
        }

        return view('admin.rapports', compact('rapportData', 'type', 'dateDebut', 'dateFin'));
    }

    /**
     * Rapport des ventes
     */
    private function rapportVentes($dateDebut, $dateFin)
    {
        $ventes = Vente::with(['user', 'client'])
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_ventes' => $ventes->count(),
            'ca_total' => $ventes->sum('montant_total'),
            'ca_moyen' => $ventes->avg('montant_total'),
            'ventes_validees' => $ventes->where('statut', 'validee')->count(),
            'ventes_annulees' => $ventes->where('statut', 'annulee')->count(),
        ];

        // Répartition par mode de paiement
        $paiements = $ventes->groupBy('mode_paiement')->map(function($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('montant_total'),
            ];
        });

        return [
            'ventes' => $ventes,
            'stats' => $stats,
            'paiements' => $paiements,
        ];
    }

    /**
     * Rapport des produits
     */
    private function rapportProduits($dateDebut, $dateFin)
    {
        $produits = Produit::select('produits.*')
            ->selectRaw('SUM(lignes_ventes.quantite) as quantite_vendue')
            ->selectRaw('SUM(lignes_ventes.prix_total_ttc) as ca_produit')
            ->selectRaw('COUNT(DISTINCT ventes.id) as nombre_ventes')
            ->leftJoin('lignes_ventes', 'produits.id', '=', 'lignes_ventes.produit_id')
            ->leftJoin('ventes', function($join) use ($dateDebut, $dateFin) {
                $join->on('lignes_ventes.vente_id', '=', 'ventes.id')
                     ->whereBetween('ventes.created_at', [$dateDebut, $dateFin]);
            })
            ->groupBy('produits.id')
            ->orderBy('quantite_vendue', 'desc')
            ->get();

        $stats = [
            'total_produits' => Produit::count(),
            'produits_vendus' => $produits->where('quantite_vendue', '>', 0)->count(),
            'ca_total' => $produits->sum('ca_produit'),
            'quantite_totale' => $produits->sum('quantite_vendue'),
        ];

        return [
            'produits' => $produits,
            'stats' => $stats,
        ];
    }

    /**
     * Rapport des clients
     */
    private function rapportClients($dateDebut, $dateFin)
    {
        $clients = Client::select('clients.*')
            ->selectRaw('COUNT(ventes.id) as total_achats')
            ->selectRaw('SUM(ventes.montant_total) as ca_client')
            ->selectRaw('MAX(ventes.created_at) as derniere_visite')
            ->leftJoin('ventes', function($join) use ($dateDebut, $dateFin) {
                $join->on('clients.id', '=', 'ventes.client_id')
                     ->whereBetween('ventes.created_at', [$dateDebut, $dateFin]);
            })
            ->groupBy('clients.id')
            ->orderBy('ca_client', 'desc')
            ->get();

        $stats = [
            'total_clients' => Client::count(),
            'clients_actifs' => $clients->where('total_achats', '>', 0)->count(),
            'ca_total' => $clients->sum('ca_client'),
            'ca_moyen' => $clients->where('total_achats', '>', 0)->avg('ca_client'),
        ];

        return [
            'clients' => $clients,
            'stats' => $stats,
        ];
    }
}