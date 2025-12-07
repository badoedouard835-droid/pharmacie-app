<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                <i class="fas fa-edit"></i> Modifier : {{ $produit->nom }}
            </h2>
            <a href="{{ route('produits.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </x-slot>

    <div class="container py-4">
        <div class="row">
            <div class="col-md-12">
                
                <form method="POST" action="{{ route('produits.update', $produit) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Colonne gauche : Photo -->
                        <div class="col-md-4">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-warning">
                                    <h6 class="mb-0"><i class="fas fa-camera"></i> Photo du produit</h6>
                                </div>
                                <div class="card-body text-center">
                                    <img id="photo-preview" 
                                         src="{{ $produit->photoUrl() }}" 
                                         alt="Aperçu" 
                                         class="img-fluid rounded mb-3"
                                         style="max-height: 300px; object-fit: cover;">
                                    
                                    <input type="file" 
                                           class="form-control @error('photo') is-invalid @enderror" 
                                           id="photo" 
                                           name="photo" 
                                           accept="image/*"
                                           data-preview="photo-preview">
                                    
                                    <small class="form-text text-muted d-block mt-2">
                                        Laissez vide pour garder la photo actuelle
                                    </small>
                                    
                                    @error('photo')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Colonne droite : Informations -->
                        <div class="col-md-8">
                            
                            <!-- Informations générales -->
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-warning">
                                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informations générales</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nom" class="form-label">
                                                Nom du produit <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('nom') is-invalid @enderror" 
                                                   id="nom" 
                                                   name="nom" 
                                                   value="{{ old('nom', $produit->nom) }}" 
                                                   required>
                                            @error('nom')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="code_barre" class="form-label">Code-barres</label>
                                            <input type="text" 
                                                   class="form-control @error('code_barre') is-invalid @enderror" 
                                                   id="code_barre" 
                                                   name="code_barre" 
                                                   value="{{ old('code_barre', $produit->code_barre) }}">
                                            @error('code_barre')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="categorie_id" class="form-label">
                                                Catégorie <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('categorie_id') is-invalid @enderror" 
                                                    id="categorie_id" 
                                                    name="categorie_id" 
                                                    required>
                                                @foreach($categories as $categorie)
                                                    <option value="{{ $categorie->id }}" 
                                                            {{ old('categorie_id', $produit->categorie_id) == $categorie->id ? 'selected' : '' }}>
                                                        {{ $categorie->nom }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('categorie_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="laboratoire" class="form-label">Laboratoire</label>
                                            <input type="text" 
                                                   class="form-control @error('laboratoire') is-invalid @enderror" 
                                                   id="laboratoire" 
                                                   name="laboratoire" 
                                                   value="{{ old('laboratoire', $produit->laboratoire) }}">
                                            @error('laboratoire')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="forme" class="form-label">Forme</label>
                                            <input type="text" 
                                                   class="form-control @error('forme') is-invalid @enderror" 
                                                   id="forme" 
                                                   name="forme" 
                                                   value="{{ old('forme', $produit->forme) }}">
                                            @error('forme')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="dosage" class="form-label">Dosage</label>
                                            <input type="text" 
                                                   class="form-control @error('dosage') is-invalid @enderror" 
                                                   id="dosage" 
                                                   name="dosage" 
                                                   value="{{ old('dosage', $produit->dosage) }}">
                                            @error('dosage')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" 
                                                  name="description" 
                                                  rows="3">{{ old('description', $produit->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Prix et Stock -->
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-dollar-sign"></i> Prix et Stock</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="prix_achat" class="form-label">
                                                Prix d'achat (FCFA) <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" 
                                                   class="form-control @error('prix_achat') is-invalid @enderror" 
                                                   id="prix_achat" 
                                                   name="prix_achat" 
                                                   value="{{ old('prix_achat', $produit->prix_achat) }}" 
                                                   step="0.01"
                                                   min="0"
                                                   required>
                                            @error('prix_achat')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="prix_vente" class="form-label">
                                                Prix de vente (FCFA) <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" 
                                                   class="form-control @error('prix_vente') is-invalid @enderror" 
                                                   id="prix_vente" 
                                                   name="prix_vente" 
                                                   value="{{ old('prix_vente', $produit->prix_vente) }}" 
                                                   step="0.01"
                                                   min="0"
                                                   required>
                                            @error('prix_vente')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="quantite_stock" class="form-label">
                                                Quantité en stock <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" 
                                                   class="form-control @error('quantite_stock') is-invalid @enderror" 
                                                   id="quantite_stock" 
                                                   name="quantite_stock" 
                                                   value="{{ old('quantite_stock', $produit->quantite_stock) }}" 
                                                   min="0"
                                                   required>
                                            @error('quantite_stock')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="stock_minimum" class="form-label">
                                                Stock minimum <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" 
                                                   class="form-control @error('stock_minimum') is-invalid @enderror" 
                                                   id="stock_minimum" 
                                                   name="stock_minimum" 
                                                   value="{{ old('stock_minimum', $produit->stock_minimum) }}" 
                                                   min="0"
                                                   required>
                                            @error('stock_minimum')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="date_expiration" class="form-label">Date d'expiration</label>
                                            <input type="date" 
                                                   class="form-control @error('date_expiration') is-invalid @enderror" 
                                                   id="date_expiration" 
                                                   name="date_expiration" 
                                                   value="{{ old('date_expiration', $produit->date_expiration ? $produit->date_expiration->format('Y-m-d') : '') }}">
                                            @error('date_expiration')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="statut" class="form-label">Statut</label>
                                            <select class="form-select @error('statut') is-invalid @enderror" 
                                                    id="statut" 
                                                    name="statut">
                                                <option value="1" {{ old('statut', $produit->statut) == 1 ? 'selected' : '' }}>Actif</option>
                                                <option value="0" {{ old('statut', $produit->statut) == 0 ? 'selected' : '' }}>Inactif</option>
                                            </select>
                                            @error('statut')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('produits.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Mettre à jour
                                </button>
                            </div>

                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>