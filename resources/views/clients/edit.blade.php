<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-dark">
                <i class="fas fa-user-edit"></i> Modifier le Client
            </h2>
            <div>
                <a href="{{ route('clients.show', $client) }}" class="btn btn-info btn-sm me-2">
                    <i class="fas fa-eye"></i> Voir la fiche
                </a>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning">
                            <h5 class="mb-0">
                                <i class="fas fa-user-edit"></i> Modification : {{ $client->nomComplet() }}
                            </h5>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('clients.update', $client) }}">
                                @csrf
                                @method('PUT')
                                {{-- @method('PUT') : indique que c'est une mise à jour --}}

                                <!-- Numéro client (non modifiable) -->
                                <div class="alert alert-secondary">
                                    <strong>Numéro client :</strong> 
                                    <span class="badge bg-dark">{{ $client->numero_client }}</span>
                                </div>

                                <!-- INFORMATIONS PERSONNELLES -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="nom" class="form-label">
                                            Nom <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('nom') is-invalid @enderror" 
                                               id="nom" 
                                               name="nom" 
                                               value="{{ old('nom', $client->nom) }}" 
                                               required>
                                        {{-- old('nom', $client->nom) : garde old() si erreur, sinon valeur actuelle --}}
                                        @error('nom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="prenom" class="form-label">
                                            Prénom <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('prenom') is-invalid @enderror" 
                                               id="prenom" 
                                               name="prenom" 
                                               value="{{ old('prenom', $client->prenom) }}" 
                                               required>
                                        @error('prenom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- CONTACT -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="telephone" class="form-label">
                                            <i class="fas fa-phone"></i> Téléphone <span class="text-danger">*</span>
                                        </label>
                                        <input type="tel" 
                                               class="form-control @error('telephone') is-invalid @enderror" 
                                               id="telephone" 
                                               name="telephone" 
                                               value="{{ old('telephone', $client->telephone) }}" 
                                               required>
                                        @error('telephone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope"></i> Email
                                        </label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', $client->email) }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- ADRESSE -->
                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <label for="adresse" class="form-label">
                                            <i class="fas fa-map-marker-alt"></i> Adresse
                                        </label>
                                        <textarea class="form-control @error('adresse') is-invalid @enderror" 
                                                  id="adresse" 
                                                  name="adresse" 
                                                  rows="2">{{ old('adresse', $client->adresse) }}</textarea>
                                        @error('adresse')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="ville" class="form-label">
                                            <i class="fas fa-city"></i> Ville
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('ville') is-invalid @enderror" 
                                               id="ville" 
                                               name="ville" 
                                               value="{{ old('ville', $client->ville) }}">
                                        @error('ville')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- INFORMATIONS COMPLÉMENTAIRES -->
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="date_naissance" class="form-label">
                                            <i class="fas fa-birthday-cake"></i> Date de naissance
                                        </label>
                                        <input type="date" 
                                               class="form-control @error('date_naissance') is-invalid @enderror" 
                                               id="date_naissance" 
                                               name="date_naissance" 
                                               value="{{ old('date_naissance', $client->date_naissance ? $client->date_naissance->format('Y-m-d') : '') }}"
                                               max="{{ date('Y-m-d') }}">
                                        @error('date_naissance')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="sexe" class="form-label">
                                            <i class="fas fa-venus-mars"></i> Sexe
                                        </label>
                                        <select class="form-select @error('sexe') is-invalid @enderror" 
                                                id="sexe" 
                                                name="sexe">
                                            <option value="">-- Sélectionner --</option>
                                            <option value="M" {{ old('sexe', $client->sexe) == 'M' ? 'selected' : '' }}>Masculin</option>
                                            <option value="F" {{ old('sexe', $client->sexe) == 'F' ? 'selected' : '' }}>Féminin</option>
                                        </select>
                                        @error('sexe')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="type" class="form-label">
                                            Type de client <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('type') is-invalid @enderror" 
                                                id="type" 
                                                name="type" 
                                                required>
                                            <option value="">-- Sélectionner --</option>
                                            <option value="particulier" {{ old('type', $client->type) == 'particulier' ? 'selected' : '' }}>
                                                Particulier
                                            </option>
                                            <option value="professionnel" {{ old('type', $client->type) == 'professionnel' ? 'selected' : '' }}>
                                                Professionnel
                                            </option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- REMARQUES -->
                                <div class="mb-3">
                                    <label for="remarques" class="form-label">
                                        <i class="fas fa-comment"></i> Remarques
                                    </label>
                                    <textarea class="form-control @error('remarques') is-invalid @enderror" 
                                              id="remarques" 
                                              name="remarques" 
                                              rows="3">{{ old('remarques', $client->remarques) }}</textarea>
                                    @error('remarques')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- BOUTONS -->
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('clients.show', $client) }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Annuler
                                    </a>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save"></i> Enregistrer les modifications
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>