<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-dark">
                <i class="fas fa-users"></i> Gestion des Clients
            </h2>
            <a href="{{ route('clients.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Nouveau client
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        @push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush
        <div class="container-fluid">

            
            <!-- ============================================ -->
            <!-- STATISTIQUES RAPIDES -->
            <!-- ============================================ -->
            <div class="row g-3 mb-4">
                <!-- Total clients -->
                <div class="col-md-3">
                    <div class="card stat-card blue text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                            <p class="mb-0">Total Clients</p>
                        </div>
                    </div>
                </div>

                <!-- Particuliers -->
                <div class="col-md-3">
                    <div class="card stat-card green text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-user fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $stats['particuliers'] }}</h3>
                            <p class="mb-0">Particuliers</p>
                        </div>
                    </div>
                </div>

                <!-- Professionnels -->
                <div class="col-md-3">
                    <div class="card stat-card orange text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-building fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $stats['professionnels'] }}</h3>
                            <p class="mb-0">Professionnels</p>
                        </div>
                    </div>
                </div>

                <!-- Nouveaux ce mois -->
                <div class="col-md-3">
                    <div class="card stat-card red text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-user-plus fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $stats['nouveaux_mois'] }}</h3>
                            <p class="mb-0">Nouveaux ce mois</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- FILTRES ET RECHERCHE -->
            <!-- ============================================ -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('clients.index') }}" class="row g-3">
                        
                        <!-- Recherche par nom/téléphone -->
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-search"></i> Rechercher
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Nom, prénom, téléphone, numéro...">
                        </div>

                        <!-- Filtre par type -->
                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="fas fa-filter"></i> Type
                            </label>
                            <select class="form-select" name="type">
                                <option value="">Tous les types</option>
                                <option value="particulier" {{ request('type') == 'particulier' ? 'selected' : '' }}>
                                    Particulier
                                </option>
                                <option value="professionnel" {{ request('type') == 'professionnel' ? 'selected' : '' }}>
                                    Professionnel
                                </option>
                            </select>
                        </div>

                        <!-- Filtre par ville -->
                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt"></i> Ville
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   name="ville" 
                                   value="{{ request('ville') }}" 
                                   placeholder="Ville">
                        </div>

                        <!-- Boutons -->
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100 me-2">
                                <i class="fas fa-search"></i> Filtrer
                            </button>
                            <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- TABLEAU DES CLIENTS -->
            <!-- ============================================ -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Liste des clients 
                        <span class="badge bg-light text-primary ms-2">{{ $clients->total() }}</span>
                    </h5>
                </div>

                <div class="card-body p-0">
                    @if($clients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° Client</th>
                                        <th>Nom Complet</th>
                                        <th>Téléphone</th>
                                        <th>Ville</th>
                                        <th>Type</th>
                                        <th>Achats</th>
                                        <th>Inscription</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clients as $client)
                                        <tr>
                                            <!-- Numéro client -->
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $client->numero_client }}
                                                </span>
                                            </td>

                                            <!-- Nom complet -->
                                            <td>
                                                <strong>{{ $client->nomComplet() }}</strong>
                                                @if($client->estBonClient())
                                                    <span class="badge bg-warning text-dark ms-1" 
                                                          title="Client fidèle">
                                                        <i class="fas fa-star"></i>
                                                    </span>
                                                @endif
                                            </td>

                                            <!-- Téléphone -->
                                            <td>
                                                <i class="fas fa-phone text-primary"></i>
                                                {{ $client->telephone }}
                                            </td>

                                            <!-- Ville -->
                                            <td>
                                                @if($client->ville)
                                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                                    {{ $client->ville }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>

                                            <!-- Type -->
                                            <td>
                                                @if($client->type == 'particulier')
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-user"></i> Particulier
                                                    </span>
                                                @else
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-building"></i> Professionnel
                                                    </span>
                                                @endif
                                            </td>

                                            <!-- Nombre d'achats -->
                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ $client->nombreAchats() }} achat(s)
                                                </span>
                                            </td>

                                            <!-- Date d'inscription -->
                                            <td>
                                                <small class="text-muted">
                                                    {{ $client->created_at->format('d/m/Y') }}
                                                </small>
                                            </td>

                                            <!-- Actions -->
                                            <td class="text-end">
                                                <div class="btn-group" role="group">
                                                    <!-- Voir -->
                                                    <a href="{{ route('clients.show', $client) }}" 
                                                       class="btn btn-sm btn-info" 
                                                       title="Voir le client">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <!-- Modifier -->
                                                    <a href="{{ route('clients.edit', $client) }}" 
                                                       class="btn btn-sm btn-warning" 
                                                       title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <!-- Supprimer -->
                                                    <form method="POST" 
                                                          action="{{ route('clients.destroy', $client) }}" 
                                                          style="display:inline;"
                                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-danger" 
                                                                title="Supprimer">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $clients->links('pagination::bootstrap-5') }}
                        </div>

                    @else
                        <!-- Aucun client trouvé -->
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun client trouvé</h5>
                            <p class="text-muted">
                                @if(request()->has('search') || request()->has('type') || request()->has('ville'))
                                    Aucun résultat pour vos critères de recherche.
                                    <br>
                                    <a href="{{ route('clients.index') }}" class="btn btn-link">
                                        Réinitialiser les filtres
                                    </a>
                                @else
                                    Commencez par ajouter votre premier client !
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>