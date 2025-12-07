<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-dark">
                <i class="fas fa-shopping-cart"></i> Gestion des Ventes
            </h2>
            <a href="{{ route('ventes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvelle vente
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            @push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush
            <!-- STATISTIQUES RAPIDES -->
            <div class="row g-3 mb-4">
                <!-- Total ventes -->
                <div class="col-md-3">
                    <div class="card stat-card blue text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $stats['total_ventes'] }}</h3>
                            <p class="mb-0">Total Ventes</p>
                        </div>
                    </div>
                </div>

                <!-- Chiffre d'affaires total -->
                <div class="col-md-3">
                    <div class="card stat-card green text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ number_format($stats['total_ca'], 0, ',', ' ') }}</h3>
                            <p class="mb-0">CA Total (FCFA)</p>
                        </div>
                    </div>
                </div>

                <!-- Ventes aujourd'hui -->
                <div class="col-md-3">
                    <div class="card stat-card orange text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-day fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $stats['ventes_jour'] }}</h3>
                            <p class="mb-0">Ventes du jour</p>
                        </div>
                    </div>
                </div>

                <!-- CA aujourd'hui -->
                <div class="col-md-3">
                    <div class="card stat-card red text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-line fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ number_format($stats['ca_jour'], 0, ',', ' ') }}</h3>
                            <p class="mb-0">CA du jour (FCFA)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FILTRES ET RECHERCHE -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('ventes.index') }}" class="row g-3">
                        
                        <!-- Recherche par numéro -->
                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="fas fa-search"></i> N° de vente
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="V2024001...">
                        </div>

                        <!-- Filtre par statut -->
                        <div class="col-md-2">
                            <label class="form-label">
                                <i class="fas fa-filter"></i> Statut
                            </label>
                            <select class="form-select" name="statut">
                                <option value="">Tous</option>
                                <option value="validee" {{ request('statut') == 'validee' ? 'selected' : '' }}>
                                    Validée
                                </option>
                                <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>
                                    En cours
                                </option>
                                <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>
                                    Annulée
                                </option>
                            </select>
                        </div>

                        <!-- Filtre par mode de paiement -->
                        <div class="col-md-2">
                            <label class="form-label">
                                <i class="fas fa-credit-card"></i> Paiement
                            </label>
                            <select class="form-select" name="mode_paiement">
                                <option value="">Tous</option>
                                <option value="especes" {{ request('mode_paiement') == 'especes' ? 'selected' : '' }}>
                                    Espèces
                                </option>
                                <option value="carte" {{ request('mode_paiement') == 'carte' ? 'selected' : '' }}>
                                    Carte
                                </option>
                                <option value="mobile_money" {{ request('mode_paiement') == 'mobile_money' ? 'selected' : '' }}>
                                    Mobile Money
                                </option>
                                <option value="cheque" {{ request('mode_paiement') == 'cheque' ? 'selected' : '' }}>
                                    Chèque
                                </option>
                            </select>
                        </div>

                        <!-- Date début -->
                        <div class="col-md-2">
                            <label class="form-label">
                                <i class="fas fa-calendar"></i> Du
                            </label>
                            <input type="date" 
                                   class="form-control" 
                                   name="date_debut" 
                                   value="{{ request('date_debut') }}">
                        </div>

                        <!-- Date fin -->
                        <div class="col-md-2">
                            <label class="form-label">
                                <i class="fas fa-calendar"></i> Au
                            </label>
                            <input type="date" 
                                   class="form-control" 
                                   name="date_fin" 
                                   value="{{ request('date_fin') }}">
                        </div>

                        <!-- Boutons -->
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100 me-2">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('ventes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TABLEAU DES VENTES -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Liste des ventes 
                        <span class="badge bg-light text-primary ms-2">{{ $ventes->total() }}</span>
                    </h5>
                </div>

                <div class="card-body p-0">
                    @if($ventes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° Vente</th>
                                        <th>Date</th>
                                        <th>Client</th>
                                        <th>Vendeur</th>
                                        <th>Montant</th>
                                        <th>Paiement</th>
                                        <th>Statut</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ventes as $vente)
                                        <tr>
                                            <!-- Numéro vente -->
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $vente->numero_vente }}
                                                </span>
                                            </td>

                                            <!-- Date -->
                                            <td>
                                                <small>
                                                    {{ $vente->date_vente->format('d/m/Y') }}
                                                    <br>
                                                    <span class="text-muted">{{ $vente->date_vente->format('H:i') }}</span>
                                                </small>
                                            </td>

                                            <!-- Client -->
                                            <td>
                                                @if($vente->client)
                                                    <a href="{{ route('clients.show', $vente->client) }}" 
                                                       class="text-decoration-none">
                                                        {{ $vente->client->nomComplet() }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Client non enregistré</span>
                                                @endif
                                            </td>

                                            <!-- Vendeur -->
                                            <td>
                                                <small class="text-muted">
                                                    {{ $vente->user->name }}
                                                </small>
                                            </td>

                                            <!-- Montant -->
                                            <td>
                                                <strong class="text-success">
                                                    {{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA
                                                </strong>
                                            </td>

                                            <!-- Mode de paiement -->
                                            <td>
                                                @switch($vente->mode_paiement)
                                                    @case('especes')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-money-bill-wave"></i> Espèces
                                                        </span>
                                                        @break
                                                    @case('carte')
                                                        <span class="badge bg-primary">
                                                            <i class="fas fa-credit-card"></i> Carte
                                                        </span>
                                                        @break
                                                    @case('mobile_money')
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="fas fa-mobile-alt"></i> Mobile Money
                                                        </span>
                                                        @break
                                                    @case('cheque')
                                                        <span class="badge bg-info">
                                                            <i class="fas fa-money-check"></i> Chèque
                                                        </span>
                                                        @break
                                                @endswitch
                                            </td>

                                            <!-- Statut -->
                                            <td>
                                                @switch($vente->statut)
                                                    @case('validee')
                                                        <span class="badge bg-success">Validée</span>
                                                        @break
                                                    @case('en_cours')
                                                        <span class="badge bg-warning">En cours</span>
                                                        @break
                                                    @case('annulee')
                                                        <span class="badge bg-danger">Annulée</span>
                                                        @break
                                                @endswitch
                                            </td>

                                            <!-- Actions -->
                                            <td class="text-end">
                                                <div class="btn-group" role="group">
                                                    <!-- Voir -->
                                                    <a href="{{ route('ventes.show', $vente) }}" 
                                                       class="btn btn-sm btn-info" 
                                                       title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <!-- Télécharger facture -->
                                                    <a href="{{ route('ventes.facture', $vente) }}" 
                                                       class="btn btn-sm btn-primary" 
                                                       title="Télécharger la facture">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>

                                                    <!-- Annuler (si validée) -->
                                                    @if($vente->statut == 'validee')
                                                        <form method="POST" 
                                                              action="{{ route('ventes.annuler', $vente) }}" 
                                                              style="display:inline;"
                                                              onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette vente ? Les stocks seront restaurés.');">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-danger" 
                                                                    title="Annuler la vente">
                                                                <i class="fas fa-ban"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $ventes->links('pagination::bootstrap-5') }}
                        </div>

                    @else
                        <!-- Aucune vente -->
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune vente enregistrée</h5>
                            <p class="text-muted">
                                @if(request()->has('search') || request()->has('statut'))
                                    Aucun résultat pour vos critères de recherche.
                                @else
                                    Commencez par créer votre première vente !
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>