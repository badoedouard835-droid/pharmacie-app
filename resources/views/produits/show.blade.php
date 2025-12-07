<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                <i class="fas fa-eye"></i> Détails du produit
            </h2>
            <div>
                <a href="{{ route('produits.edit', $produit) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <a href="{{ route('produits.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row">
            
            <!-- Colonne gauche : Photo et infos rapides -->
            <div class="col-md-4">
                
                <!-- Photo -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center">
                        <img src="{{ $produit->photoUrl() }}" 
                             alt="{{ $produit->nom }}" 
                             class="img-fluid rounded"
                             style="max-height: 400px; object-fit: cover;">
                    </div>
                </div>

                <!-- Alertes -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning">
                        <h6 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Alertes</h6>
                    </div>
                    <div class="card-body">
                        <!-- Alerte stock -->
                        @if($produit->isRupture())
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle"></i> 
                                <strong>Rupture de stock !</strong><br>
                                Quantité actuelle : {{ $produit->quantite_stock }}
                            </div>
                        @elseif($produit->isStockFaible())
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-circle"></i> 
                                <strong>Stock faible !</strong><br>
                                Quantité actuelle : {{ $produit->quantite_stock }}<br>
                                Seuil minimum : {{ $produit->stock_minimum }}
                            </div>
                        @else
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> 
                                <strong>Stock OK</strong><br>
                                Quantité : {{ $produit->quantite_stock }} unités
                            </div>
                        @endif

                        <!-- Alerte expiration -->
                        @if($produit->date_expiration)
                            @if($produit->isPerime())
                                <div class="alert alert-danger">
                                    <i class="fas fa-calendar-times"></i> 
                                    <strong>Produit périmé !</strong><br>
                                    Expiré le : {{ $produit->date_expiration->format('d/m/Y') }}
                                </div>
                            @elseif($produit->isProchePeriemption())
                                <div class="alert alert-warning">
                                    <i class="fas fa-calendar-exclamation"></i> 
                                    <strong>Expire bientôt !</strong><br>
                                    Dans {{ $produit->joursAvantExpiration() }} jour(s)<br>
                                    Date : {{ $produit->date_expiration->format('d/m/Y') }}
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-calendar-check"></i> 
                                    <strong>Validité OK</strong><br>
                                    Expire le : {{ $produit->date_expiration->format('d/m/Y') }}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-chart-line"></i> Statistiques</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Marge bénéficiaire</strong>
                            <div class="progress mt-2" style="height: 25px;">
                                <div class="progress-bar bg-success" 
                                     role="progressbar" 
                                     style="width: {{ min($produit->marge(), 100) }}%">
                                    {{ number_format($produit->marge(), 1) }}%
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Bénéfice unitaire :</span>
                            <strong>{{ number_format($produit->prix_vente - $produit->prix_achat, 0, ',', ' ') }} FCFA</strong>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span>Valeur du stock :</span>
                            <strong>{{ number_format($produit->quantite_stock * $produit->prix_vente, 0, ',', ' ') }} FCFA</strong>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Colonne droite : Informations détaillées -->
            <div class="col-md-8">
                
                <!-- Informations générales -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations générales</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h4 class="text-primary">{{ $produit->nom }}</h4>
                                <p class="text-muted">{{ $produit->description ?? 'Pas de description' }}</p>
                            </div>
                            <div class="col-md-6 text-end">
                                @if($produit->statut)
                                    <span class="badge bg-success fs-6">Actif</span>
                                @else
                                    <span class="badge bg-danger fs-6">Inactif</span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-barcode text-primary me-2 fs-5"></i>
                                    <div>
                                        <small class="text-muted d-block">Code-barres</small>
                                        <strong>{{ $produit->code_barre ?? 'Non renseigné' }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-tag text-primary me-2 fs-5"></i>
                                    <div>
                                        <small class="text-muted d-block">Catégorie</small>
                                        <strong>{{ $produit->categorie->nom }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-flask text-primary me-2 fs-5"></i>
                                    <div>
                                        <small class="text-muted d-block">Laboratoire</small>
                                        <strong>{{ $produit->laboratoire ?? 'Non renseigné' }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-pills text-primary me-2 fs-5"></i>
                                    <div>
                                        <small class="text-muted d-block">Forme</small>
                                        <strong>{{ $produit->forme ?? 'Non renseigné' }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-weight text-primary me-2 fs-5"></i>
                                    <div>
                                        <small class="text-muted d-block">Dosage</small>
                                        <strong>{{ $produit->dosage ?? 'Non renseigné' }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar text-primary me-2 fs-5"></i>
                                    <div>
                                        <small class="text-muted d-block">Date d'expiration</small>
                                        <strong>
                                            {{ $produit->date_expiration ? $produit->date_expiration->format('d/m/Y') : 'Non renseignée' }}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prix -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-dollar-sign"></i> Prix</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted d-block">Prix d'achat</small>
                                    <h3 class="text-primary mb-0">
                                        {{ number_format($produit->prix_achat, 0, ',', ' ') }} <small>FCFA</small>
                                    </h3>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted d-block">Prix de vente</small>
                                    <h3 class="text-success mb-0">
                                        {{ number_format($produit->prix_vente, 0, ',', ' ') }} <small>FCFA</small>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stock -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-boxes"></i> Gestion du stock</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-box text-info fs-2 mb-2"></i>
                                    <h4 class="mb-0">{{ $produit->quantite_stock }}</h4>
                                    <small class="text-muted">Quantité en stock</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-exclamation-triangle text-warning fs-2 mb-2"></i>
                                    <h4 class="mb-0">{{ $produit->stock_minimum }}</h4>
                                    <small class="text-muted">Seuil minimum</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-chart-line text-success fs-2 mb-2"></i>
                                    <h4 class="mb-0">{!! $produit->badgeStock() !!}</h4>
                                    <small class="text-muted">État du stock</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historique -->
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-history"></i> Historique</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">Créé le :</small><br>
                                <strong>{{ $produit->created_at->format('d/m/Y à H:i') }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Dernière modification :</small><br>
                                <strong>{{ $produit->updated_at->format('d/m/Y à H:i') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>