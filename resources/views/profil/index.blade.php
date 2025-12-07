<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                <i class="fas fa-user-circle"></i> Mon Profil
            </h2>
            <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
           <i class="fas fa-edit"> </i> Modifier le profil
            </a>
        </div>
    </x-slot>

    <div class="container py-4">
        <div class="row">
            
            <!-- COLONNE GAUCHE : Photo et infos rapides -->
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center">
                        <!-- Photo de profil -->
                        <div class="mb-3">
                            @if($user->photo)
                                <img src="{{ $user->photoUrl() }}" 
                                     alt="Photo de profil" 
                                     class="rounded-circle shadow"
                                     width="150" 
                                     height="150"
                                     style="object-fit: cover;">
                            @else
                                <div class="bg-primary text-white rounded-circle mx-auto shadow d-flex align-items-center justify-content-center" 
                                     style="width: 150px; height: 150px; font-size: 3rem;">
                                    {{ $user->initiales() }}
                                </div>
                            @endif
                        </div>

                        <!-- Nom et rôle -->
                        <h4 class="mb-1">{{ $user->name }}</h4>
                        <p class="text-muted mb-3">
                            <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                            @if($user->statut)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-danger">Inactif</span>
                            @endif
                        </p>

                        <!-- Boutons d'action photo -->
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#photoModal">
                                <i class="fas fa-camera"></i> 
                                {{ $user->photo ? 'Changer la photo' : 'Ajouter une photo' }}
                            </button>

                            @if($user->photo)
                                <form method="POST" action="{{ route('profile.delete-photo') }}" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre photo ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                        <i class="fas fa-trash"></i> Supprimer la photo
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Card Statistiques -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Statistiques</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Membre depuis</span>
                                <strong>{{ $user->created_at->format('d/m/Y') }}</strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- COLONNE DROITE : Détails du profil -->
            <div class="col-md-8">
                
                <!-- Card Informations personnelles -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle"></i> Informations personnelles
                        </h5>
                        <a href="{{ route('profile.edit') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong><i class="fas fa-user text-primary"></i> Nom complet</strong>
                            </div>
                            <div class="col-md-8">
                                {{ $user->name }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong><i class="fas fa-envelope text-primary"></i> Email</strong>
                            </div>
                            <div class="col-md-8">
                                {{ $user->email }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong><i class="fas fa-phone text-primary"></i> Téléphone</strong>
                            </div>
                            <div class="col-md-8">
                                {{ $user->telephone ?? 'Non renseigné' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong><i class="fas fa-map-marker-alt text-primary"></i> Adresse</strong>
                            </div>
                            <div class="col-md-8">
                                {{ $user->adresse ?? 'Non renseignée' }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <strong><i class="fas fa-user-tag text-primary"></i> Rôle</strong>
                            </div>
                            <div class="col-md-8">
                                <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Sécurité -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-shield-alt"></i> Sécurité
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <strong>Mot de passe</strong>
                                <p class="text-muted mb-0 small">
                                    Dernière modification : {{ $user->updated_at->format('d/m/Y à H:i') }}
                                </p>
                            </div>
                            <button type="button" class="btn btn-warning btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#passwordModal">
                                <i class="fas fa-key"></i> Changer
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL : Changer la photo -->
    <div class="modal fade" id="photoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-camera"></i> Changer la photo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <form method="POST" action="{{ route('profile.upload-photo') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            <img id="photo-preview" 
                                 src="{{ $user->photoUrl() }}" 
                                 alt="Aperçu" 
                                 class="rounded-circle shadow"
                                 width="150" 
                                 height="150"
                                 style="object-fit: cover;">
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">Sélectionner une photo</label>
                            <input type="file" 
                                   class="form-control @error('photo') is-invalid @enderror" 
                                   id="photo" 
                                   name="photo" 
                                   accept="image/*"
                                   required>
                            <small class="form-text text-muted">
                                Formats acceptés : JPG, PNG, JPEG (max 2 Mo)
                            </small>
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Uploader
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL : Changer le mot de passe -->
    <div class="modal fade" id="passwordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="fas fa-key"></i> Changer le mot de passe
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <form method="POST" action="{{ route('profile.change-password') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                Ancien mot de passe <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password" 
                                   required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Nouveau mot de passe <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                            <small class="form-text text-muted">Minimum 8 caractères</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">
                                Confirmer le mot de passe <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-check"></i> Changer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>