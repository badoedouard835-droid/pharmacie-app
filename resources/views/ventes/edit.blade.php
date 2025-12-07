<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                <i class="fas fa-edit"></i> Modifier la vente N° {{ $vente->numero_vente }}
            </h2>
            <a href="{{ route('ventes.show', $vente) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                
                <form method="POST" action="{{ route('ventes.update', $vente) }}" id="form-vente">
                    @csrf
                    @method('PUT')

                    <!-- Informations générales -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-warning">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations générales</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="client_id" class="form-label">Client</label>
                                    <select class="form-select @error('client_id') is-invalid @enderror" 
                                            id="client_id" 
                                            name="client_id">
                                        <option value="">Vente sans client</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" 
                                                    {{ old('client_id', $vente->client_id) == $client->id ? 'selected' : '' }}>
                                                {{ $client->nomComplet() }} - {{ $client->telephone }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="date_vente" class="form-label">
                                        Date de vente <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" 
                                           class="form-control @error('date_vente') is-invalid @enderror" 
                                           id="date_vente" 
                                           name="date_vente" 
                                           value="{{ old('date_vente', $vente->date_vente->format('Y-m-d\TH:i')) }}" 
                                           required>
                                    @error('date_vente')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="mode_paiement" class="form-label">
                                        Mode de paiement <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('mode_paiement') is-invalid @enderror" 
                                            id="mode_paiement" 
                                            name="mode_paiement" 
                                            required>
                                        <option value="especes" {{ old('mode_paiement', $vente->mode_paiement) == 'especes' ? 'selected' : '' }}>
                                            Espèces
                                        </option>
                                        <option value="carte" {{ old('mode_paiement', $vente->mode_paiement) == 'carte' ? 'selected' : '' }}>
                                            Carte bancaire
                                        </option>
                                        <option value="mobile_money" {{ old('mode_paiement', $vente->mode_paiement) == 'mobile_money' ? 'selected' : '' }}>
                                            Mobile Money
                                        </option>
                                        <option value="cheque" {{ old('mode_paiement', $vente->mode_paiement) == 'cheque' ? 'selected' : '' }}>
                                            Chèque
                                        </option>
                                    </select>
                                    @error('mode_paiement')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="statut" class="form-label">
                                        Statut <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('statut') is-invalid @enderror" 
                                            id="statut" 
                                            name="statut" 
                                            required>
                                        <option value="en_cours" {{ old('statut', $vente->statut) == 'en_cours' ? 'selected' : '' }}>
                                            En cours
                                        </option>
                                        <option value="validee" {{ old('statut', $vente->statut) == 'validee' ? 'selected' : '' }}>
                                            Validée
                                        </option>
                                        <option value="annulee" {{ old('statut', $vente->statut) == 'annulee' ? 'selected' : '' }}>
                                            Annulée
                                        </option>
                                    </select>
                                    @error('statut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="remarques" class="form-label">Remarques</label>
                                <textarea class="form-control @error('remarques') is-invalid @enderror" 
                                          id="remarques" 
                                          name="remarques" 
                                          rows="3">{{ old('remarques', $vente->remarques) }}</textarea>
                                @error('remarques')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Produits (affichage uniquement) -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-box"></i> Produits de la vente</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle"></i>
                                <strong>Note :</strong> La modification des produits n'est pas encore disponible. 
                                Pour modifier les produits, veuillez annuler cette vente et en créer une nouvelle.
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Produit</th>
                                            <th>Quantité</th>
                                            <th>Prix unitaire</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($vente->lignesVentes as $ligne)
                                            <tr>
                                                <td>{{ $ligne->produit->nom }}</td>
                                                <td>{{ $ligne->quantite }}</td>
                                                <td>{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                                                <td>{{ number_format($ligne->montant_total, 0, ',', ' ') }} FCFA</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-primary">
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>TOTAL :</strong></td>
                                            <td><strong>{{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('ventes.show', $vente) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Mettre à jour
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>