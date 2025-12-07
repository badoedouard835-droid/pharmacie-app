<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-dark">
                <i class="fas fa-user"></i> Fiche Client : {{ $client->nomComplet() }}
            </h2>
            <div>
                <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning btn-sm me-2">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <div class="row">
                
                <!-- COLONNE GAUCHE -->
                <div class="col-md-4">
                    
                    <!-- Card Informations principales -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white text-center">
                            <h5 class="mb-0">
                                <i class="fas fa-id-card"></i> Informations Client
                            </h5>
                        </div>
                        <div class="card-body">

                            <div class="text-center mb-3">
                                <h3 class="badge bg-dark fs-5">{{ $client->numero_client }}</h3>
                                @if($client->estBonClient())
                                    <div class="mt-2">
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-star"></i> Client Fidèle
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <hr>

                            <div class="mb-2">
                                <strong><i class="fas fa-user text-primary"></i> Nom complet</strong>
                                <p class="mb-0">{{ $client->nomComplet() }}</p>
                            </div>

                            <div class="mb-2">
                                <strong><i class="fas fa-phone text-primary"></i> Téléphone</strong>
                                <p class="mb-0">{{ $client->telephone }}</p>
                            </div>

                            @if($client->email)
                                <div class="mb-2">
                                    <strong><i class="fas fa-envelope text-primary"></i> Email</strong>
                                    <p class="mb-0">{{ $client->email }}</p>
                                </div>
                            @endif

                            @if($client->adresse)
                                <div class="mb-2">
                                    <strong><i class="fas fa-map-marker-alt text-primary"></i> Adresse</strong>
                                    <p class="mb-0">{{ $client->adresse }}</p>
                                </div>
                            @endif

                            @if($client->ville)
                                <div class="mb-2">
                                    <strong><i class="fas fa-city text-primary"></i> Ville</strong>
                                    <p class="mb-0">{{ $client->ville }}</p>
                                </div>
                            @endif

                            <div class="mb-2">
                                <strong><i class="fas fa-user-tag text-primary"></i> Type</strong>
                                <p class="mb-0">
                                    @if($client->type == 'particulier')
                                        <span class="badge bg-info">Particulier</span>
                                    @else
                                        <span class="badge bg-success">Professionnel</span>
                                    @endif
                                </p>
                            </div>

                            @if($client->date_naissance)
                                <div class="mb-2">
                                    <strong><i class="fas fa-birthday-cake text-primary"></i> Date de naissance</strong>
                                    <p class="mb-0">
                                        {{ $client->date_naissance->format('d/m/Y') }}
                                        @if($client->age())
                                            ({{ $client->age() }} ans)
                                        @endif
                                    </p>
                                </div>
                            @endif

                            @if($client->sexe)
                                <div class="mb-2">
                                    <strong><i class="fas fa-venus-mars text-primary"></i> Sexe</strong>
                                    <p class="mb-0">{{ $client->sexe == 'M' ? 'Masculin' : 'Féminin' }}</p>
                                </div>
                            @endif

                            <div class="mb-0">
                                <strong><i class="fas fa-calendar text-primary"></i> Client depuis</strong>
                                <p class="mb-0">{{ $client->created_at->format('d/m/Y') }}</p>
                            </div>

                            @if($client->remarques)
                                <hr>
                                <div class="mb-0">
                                    <strong><i class="fas fa-comment text-primary"></i> Remarques</strong>
                                    <p class="mb-0 text-muted">{{ $client->remarques }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card Statistiques -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-chart-bar"></i> Statistiques d'achat
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-shopping-cart text-success"></i> Nombre d'achats</span>
                                    <strong class="badge bg-primary fs-6">{{ $stats['nombre_achats'] }}</strong>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-money-bill-wave text-success"></i> Total dépensé</span>
                                    <strong class="text-success">{{ number_format($stats['total_achats'], 0, ',', ' ') }} FCFA</strong>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-chart-line text-success"></i> Panier moyen</span>
                                    <strong>{{ number_format($stats['panier_moyen'], 0, ',', ' ') }} FCFA</strong>
                                </div>
                            </div>

                            @if($stats['dernier_achat'])
                                <div class="mb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-clock text-success"></i> Dernier achat</span>
                                        <strong>{{ $stats['dernier_achat'] }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                <!-- COLONNE DROITE -->
                <div class="col-md-8">
                    
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-history"></i> Historique des achats
                                <span class="badge bg-light text-info ms-2">{{ $ventes->count() }}</span>
                            </h5>

                            <!-- Bouton Nouvelle vente pré-sélectionnant le client -->
                            <a href="{{ route('ventes.create', ['client_id' => $client->id]) }}" 
                               class="btn btn-light btn-sm">
                                <i class="fas fa-plus"></i> Nouvelle vente
                            </a>
                        </div>

                        <div class="card-body p-0">
                            @if($ventes->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>N° Vente</th>
                                                <th>Date</th>
                                                <th>Montant</th>
                                                <th>Mode Paiement</th>
                                                <th>Vendeur</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($ventes as $vente)
                                                <tr>

                                                    <!-- Numéro de vente -->
                                                    <td>
                                                        <span class="badge bg-secondary">
                                                            {{ $vente->numero_vente }}
                                                        </span>
                                                    </td>

                                                    <!-- Date -->
                                                    <td>
                                                        <small>
                                                            {{ $vente->created_at->format('d/m/Y H:i') }}
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

                                                    <!-- Vendeur -->
                                                    <td>
                                                        <small class="text-muted">
                                                            {{ $vente->user->name }}
                                                        </small>
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
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-info disabled" title="Voir la vente">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>

                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">Aucun achat enregistré</h5>
                                    <p class="text-muted">Ce client n'a pas encore effectué d'achat.</p>

                                    <a href="{{ route('ventes.create', ['client_id' => $client->id]) }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Créer une vente
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
