<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                <i class="fas fa-receipt"></i> Facture N° {{ $vente->numero_vente }}
            </h2>
            <div>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print"></i> Imprimer
                </button>
                <a href="{{ route('ventes.edit', $vente) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <a href="{{ route('ventes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                
                <!-- Facture -->
                <div class="card shadow-sm" id="facture">
                    <div class="card-body p-5">
                        
                        <!-- En-tête de la facture -->
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <h2 class="text-primary mb-3">
                                    <i class="fas fa-pills"></i> {{ config('app.name') }}
                                </h2>
                                <p class="mb-1"><strong>Adresse :</strong> Ouagadougou, Burkina Faso</p>
                                <p class="mb-1"><strong>Téléphone :</strong> +226 XX XX XX XX</p>
                                <p class="mb-1"><strong>Email :</strong> contact@pharmacie.bf</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <h3 class="text-primary mb-3">FACTURE</h3>
                                <p class="mb-1"><strong>N° :</strong> {{ $vente->numero_vente }}</p>
                                <p class="mb-1"><strong>Date :</strong> {{ $vente->date_vente->format('d/m/Y à H:i') }}</p>
                                <p class="mb-1">
                                    <strong>Statut :</strong> 
                                    @if($vente->statut === 'validee')
                                        <span class="badge bg-success">Validée</span>
                                    @elseif($vente->statut === 'en_cours')
                                        <span class="badge bg-warning">En cours</span>
                                    @else
                                        <span class="badge bg-danger">Annulée</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <hr>

                        <!-- Informations client et vendeur -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">Informations Client</h5>
                                @if($vente->client)
                                    <p class="mb-1"><strong>Nom :</strong> {{ $vente->client->nomComplet() }}</p>
                                    <p class="mb-1"><strong>N° Client :</strong> {{ $vente->client->numero_client }}</p>
                                    <p class="mb-1"><strong>Téléphone :</strong> {{ $vente->client->telephone }}</p>
                                    @if($vente->client->email)
                                        <p class="mb-1"><strong>Email :</strong> {{ $vente->client->email }}</p>
                                    @endif
                                    @if($vente->client->adresse)
                                        <p class="mb-1"><strong>Adresse :</strong> {{ $vente->client->adresse }}</p>
                                    @endif
                                @else
                                    <p class="text-muted">Client anonyme / Vente sans client enregistré</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">Vendeur</h5>
                                <p class="mb-1"><strong>Nom :</strong> {{ $vente->user->name }}</p>
                                <p class="mb-1"><strong>Rôle :</strong> {{ ucfirst($vente->user->role) }}</p>
                            </div>
                        </div>

                        <hr>

                        <!-- Tableau des produits -->
                        <h5 class="text-primary mb-3">Détails de la vente</h5>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Produit</th>
                                        <th class="text-center">Quantité</th>
                                        <th class="text-end">Prix unitaire</th>
                                        <th class="text-end">Remise</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vente->lignesVentes ?? [] as $ligne)
                                        <tr>
                                            <td>
                                                <strong>{{ $ligne->produit->nom }}</strong>
                                                @if($ligne->produit->code_barre)
                                                    <br><small class="text-muted">Code: {{ $ligne->produit->code_barre }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $ligne->quantite }}</td>
                                            <td class="text-end">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                                            <td class="text-end">{{ number_format($ligne->remise, 0, ',', ' ') }} FCFA</td>
                                            <td class="text-end">
                                                <strong>{{ number_format($ligne->montant_total, 0, ',', ' ') }} FCFA</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Totaux -->
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Mode de paiement :</strong> 
                                    @switch($vente->mode_paiement)
                                        @case('especes')
                                            <span class="badge bg-success">Espèces</span>
                                            @break
                                        @case('carte')
                                            <span class="badge bg-info">Carte bancaire</span>
                                            @break
                                        @case('mobile_money')
                                            <span class="badge bg-warning">Mobile Money</span>
                                            @break
                                        @case('cheque')
                                            <span class="badge bg-secondary">Chèque</span>
                                            @break
                                    @endswitch
                                </p>
                                @if($vente->remarques)
                                    <p class="mb-2"><strong>Remarques :</strong> {{ $vente->remarques }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="text-end"><strong>Montant HT :</strong></td>
                                        <td class="text-end">{{ number_format($vente->montant_ht, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><strong>TVA :</strong></td>
                                        <td class="text-end">{{ number_format($vente->montant_tva, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                    @if($vente->remise > 0)
                                        <tr>
                                            <td class="text-end"><strong>Remise :</strong></td>
                                            <td class="text-end text-danger">-{{ number_format($vente->remise, 0, ',', ' ') }} FCFA</td>
                                        </tr>
                                    @endif
                                    <tr class="table-primary">
                                        <td class="text-end"><h5 class="mb-0">TOTAL TTC :</h5></td>
                                        <td class="text-end"><h5 class="mb-0">{{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA</h5></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <hr>

                        <!-- Pied de page -->
                        <div class="text-center mt-5">
                            <p class="text-muted mb-1">Merci pour votre confiance !</p>
                            <p class="text-muted small">
                                Cette facture a été générée électroniquement le {{ now()->format('d/m/Y à H:i') }}
                            </p>
                        </div>

                    </div>
                </div>

                <!-- Actions -->
                @if($vente->statut !== 'annulee')
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-warning">
                            <h6 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Actions</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" 
                                  action="{{ route('ventes.annuler', $vente) }}" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette vente ? Cette action est irréversible.');">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times"></i> Annuler cette vente
                                </button>
                                <small class="text-muted d-block mt-2">
                                    L'annulation remettra les quantités en stock et marquera la vente comme annulée.
                                </small>
                            </form>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @media print {
            /* Masquer les éléments non nécessaires à l'impression */
            .navbar, footer, .btn, x-slot, header, .card-header.bg-warning {
                display: none !important;
            }
            
            /* Ajuster la mise en page */
            #facture {
                box-shadow: none !important;
                border: none !important;
            }
            
            body {
                background: white !important;
            }
            
            .container {
                max-width: 100% !important;
            }
        }
    </style>
    @endpush
</x-app-layout>
