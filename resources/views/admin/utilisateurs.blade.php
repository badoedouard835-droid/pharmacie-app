<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-white">
                <i class="fas fa-users-cog"></i> Gestion des Utilisateurs
            </h2>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            
            <!-- Statistiques rapides -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card stat-card blue text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                            <p class="mb-0">Total utilisateurs</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card green text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-user-shield fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $stats['admins'] }}</h3>
                            <p class="mb-0">Administrateurs</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card orange text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-user-tie fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $stats['vendeurs'] }}</h3>
                            <p class="mb-0">Vendeurs</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card red text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-user-md fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $stats['pharmaciens'] }}</h3>
                            <p class="mb-0">Pharmaciens</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres et recherche -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.utilisateurs') }}" class="row g-3">
                        
                        <div class="col-md-5">
                            <label class="form-label fw-bold">
                                <i class="fas fa-search"></i> Rechercher
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Nom, email...">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-filter"></i> Rôle
                            </label>
                            <select class="form-select" name="role">
                                <option value="">Tous les rôles</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>
                                    Administrateur
                                </option>
                                <option value="vendeur" {{ request('role') == 'vendeur' ? 'selected' : '' }}>
                                    Vendeur
                                </option>
                                <option value="pharmacien" {{ request('role') == 'pharmacien' ? 'selected' : '' }}>
                                    Pharmacien
                                </option>
                            </select>
                        </div>

                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-search"></i> Filtrer
                            </button>
                            <a href="{{ route('admin.utilisateurs') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bouton Envoyer une alerte aux vendeurs -->
            <div class="mb-4">
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalAlerte">
                    <i class="fas fa-bell"></i> Envoyer une notification
                </button>
            </div>

            <!-- Liste des utilisateurs -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Liste des utilisateurs 
                        <span class="badge bg-light text-primary ms-2">{{ $utilisateurs->total() }}</span>
                    </h5>
                </div>

                <div class="card-body p-0">
                    @if($utilisateurs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th class="text-center">Ventes</th>
                                        <th>Inscription</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($utilisateurs as $utilisateur)
                                        <tr>
                                            <!-- Photo -->
                                            <td>
                                                @if($utilisateur->photo)
                                                    <img src="{{ $utilisateur->photoUrl() }}" 
                                                         class="rounded-circle" 
                                                         width="40" 
                                                         height="40" 
                                                         style="object-fit: cover;"
                                                         alt="Photo de {{ $utilisateur->name }}">
                                                @else
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" 
                                                         style="width: 40px; height: 40px;">
                                                        {{ $utilisateur->initiales() }}
                                                    </div>
                                                @endif
                                            </td>

                                            <!-- Nom -->
                                            <td>
                                                <strong>{{ $utilisateur->name }}</strong>
                                                @if($utilisateur->id == auth()->id())
                                                    <span class="badge bg-info ms-1">Vous</span>
                                                @endif
                                            </td>

                                            <!-- Email -->
                                            <td>
                                                <i class="fas fa-envelope text-primary"></i>
                                                {{ $utilisateur->email }}
                                            </td>

                                            <!-- Rôle -->
                                            <td>
                                                @switch($utilisateur->role)
                                                    @case('admin')
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-user-shield"></i> Administrateur
                                                        </span>
                                                        @break
                                                    @case('vendeur')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-user-tie"></i> Vendeur
                                                        </span>
                                                        @break
                                                    @case('pharmacien')
                                                        <span class="badge bg-primary">
                                                            <i class="fas fa-user-md"></i> Pharmacien
                                                        </span>
                                                        @break
                                                @endswitch
                                            </td>

                                            <!-- Nombre de ventes -->
                                            <td class="text-center">
                                                <span class="badge bg-info">
                                                    {{ $utilisateur->ventes_count ?? 0 }} vente(s)
                                                </span>
                                            </td>

                                            <!-- Date d'inscription -->
                                            <td>
                                                <small class="text-muted">
                                                    {{ $utilisateur->created_at->format('d/m/Y') }}
                                                </small>
                                            </td>

                                            <!-- Actions -->
                                            <td class="text-end">
                                                <a href="{{ route('admin.utilisateur.profil', $utilisateur->id) }}" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Voir le profil">
                                                    <i class="fas fa-eye"></i> Profil
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $utilisateurs->links('pagination::bootstrap-5') }}
                        </div>

                    @else
                        <!-- Aucun utilisateur trouvé -->
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun utilisateur trouvé</h5>
                            <p class="text-muted">
                                @if(request()->has('search') || request()->has('role'))
                                    Aucun résultat pour vos critères de recherche.
                                    <br>
                                    <a href="{{ route('admin.utilisateurs') }}" class="btn btn-link">
                                        Réinitialiser les filtres
                                    </a>
                                @else
                                    Aucun utilisateur dans le système.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Modal pour envoyer une notification -->
    <div class="modal fade" id="modalAlerte" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="fas fa-bell"></i> Envoyer une notification
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="{{ route('admin.envoyer-alerte') }}">
                    @csrf

                    <div class="modal-body">
                        <!-- Sélection des destinataires -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-users"></i> Destinataires
                            </label>
                            <select name="destinataires[]" 
                                    class="form-select @error('destinataires') is-invalid @enderror" 
                                    multiple
                                    required
                                    size="6">
                                <optgroup label="Filtres rapides">
                                    <option value="tous_vendeurs">✉️ Tous les vendeurs</option>
                                    <option value="tous_pharmaciens">✉️ Tous les pharmaciens</option>
                                    <option value="tous_admins">✉️ Tous les administrateurs</option>
                                    <option value="tous">✉️ Tous les utilisateurs</option>
                                </optgroup>
                                <optgroup label="Vendeurs">
                                    @foreach($utilisateurs as $user)
                                        @if($user->role === 'vendeur')
                                            <option value="user_{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endif
                                    @endforeach
                                </optgroup>
                                <optgroup label="Pharmaciens">
                                    @foreach($utilisateurs as $user)
                                        @if($user->role === 'pharmacien')
                                            <option value="user_{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endif
                                    @endforeach
                                </optgroup>
                                <optgroup label="Administrateurs">
                                    @foreach($utilisateurs as $user)
                                        @if($user->role === 'admin')
                                            <option value="user_{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endif
                                    @endforeach
                                </optgroup>
                            </select>
                            <small class="text-muted d-block mt-2">💡 Ctrl+Clic pour sélectionner plusieurs utilisateurs</small>
                            @error('destinataires')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Titre de l'alerte -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-heading"></i> Titre
                            </label>
                            <input type="text" 
                                   name="titre" 
                                   class="form-control @error('titre') is-invalid @enderror" 
                                   placeholder="Exemple: Mise à jour importante"
                                   required>
                            @error('titre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Message de l'alerte -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-message"></i> Message
                            </label>
                            <textarea name="message" 
                                      class="form-control @error('message') is-invalid @enderror" 
                                      rows="5" 
                                      placeholder="Entrez votre message ici..."
                                      required></textarea>
                            @error('message')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type d'alerte -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-exclamation"></i> Type d'alerte
                            </label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">-- Sélectionner un type --</option>
                                <option value="info">ℹ️ Information</option>
                                <option value="success">✅ Succès</option>
                                <option value="warning">⚠️ Avertissement</option>
                                <option value="danger">🚨 Danger/Urgent</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-paper-plane"></i> Envoyer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>