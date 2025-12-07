<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-white">
                <a href="{{ route('admin.utilisateurs') }}" class="text-white text-decoration-none">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <i class="fas fa-user-circle ms-2"></i> Profil de {{ $user->name }}
            </h2>
            <div>
                <span class="badge bg-light text-primary fs-6">{{ ucfirst($user->role) }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            
            <!-- Carte profil utilisateur -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            @if($user->photo)
                                <img src="{{ $user->photoUrl() }}" class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover; border: 4px solid #1E3A8A;">
                            @else
                                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 120px; height: 120px; background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%); color: white; font-size: 48px; font-weight: bold;">
                                    {{ $user->initiales() }}
                                </div>
                            @endif
                            
                            <h4 class="mb-1">{{ $user->name }}</h4>
                            <p class="text-muted mb-2">{{ $user->email }}</p>
                            <span class="badge bg-primary mb-3">{{ ucfirst($user->role) }}</span>
                            
                            <hr>
                            
                            <div class="text-start">
                                <p class="mb-2">
                                    <i class="fas fa-calendar text-primary"></i>
                                    <strong>Membre depuis:</strong><br>
                                    <small class="ms-4">{{ $user->created_at->format('d/m/Y') }}</small>
                                    <br>
                                    <small class="ms-4 text-muted">{{ $stats['membre_depuis'] }}</small>
                                </p>
                                
                                @if($user->telephone)
                                <p class="mb-2">
                                    <i class="fas fa-phone text-primary"></i>
                                    <strong>Téléphone:</strong><br>
                                    <small class="ms-4">{{ $user->telephone }}</small>
                                </p>
                                @endif
                                
                                @if($user->adresse)
                                <p class="mb-2">
                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                    <strong>Adresse:</strong><br>
                                    <small class="ms-4">{{ $user->adresse }}</small>
                                </p>
                                @endif
                            </div>

                            <hr>

                            <!-- Changer le rôle -->
                            <form method="POST" action="{{ route('admin.utilisateur.role', $user) }}" class="mt-3">
                                @csrf
                                @method('PUT')
                                <label class="form-label fw-bold">Changer le rôle</label>
                                <div class="input-group">
                                    <select name="role" class="form-select">
                                        <option value="vendeur" {{ $user->role == 'vendeur' ? 'selected' : '' }}>Vendeur</option>
                                        <option value="pharmacien" {{ $user->role == 'pharmacien' ? 'selected' : '' }}>Pharmacien</option>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </form>

                            <hr>

                            <!-- Bouton Supprimer -->
                            @if($user->id != auth()->id())
                                <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#modalSupprimer">
                                    <i class="fas fa-trash"></i> Supprimer cet utilisateur
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Statistiques de performance -->
                <div class="col-md-8">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="card stat-card blue text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                    <h3 class="mb-0">{{ $stats['total_ventes'] }}</h3>
                                    <p class="mb-0">Ventes totales</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card stat-card green text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                                    <h3 class="mb-0">{{ number_format($stats['ca_total'], 0, ',', ' ') }}</h3>
                                    <p class="mb-0">CA Total (FCFA)</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card stat-card orange text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                    <h3 class="mb-0">{{ number_format($stats['ca_mois'], 0, ',', ' ') }}</h3>
                                    <p class="mb-0">CA ce mois (FCFA)</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card stat-card red text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-ban fa-2x mb-2"></i>
                                    <h3 class="mb-0">{{ $stats['ventes_annulees'] }}</h3>
                                    <p class="mb-0">Ventes annulées</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Graphique performance -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-chart-line"></i> Performance (6 derniers mois)</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="chartPerformance" height="80"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique des ventes -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Historique des ventes</h5>
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
                                        <th>Montant</th>
                                        <th>Paiement</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ventes as $vente)
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary">{{ $vente->numero_vente }}</span>
                                            </td>
                                            <td>
                                                <small>{{ $vente->created_at->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td>
                                                @if($vente->client)
                                                    {{ $vente->client->nomComplet() }}
                                                @else
                                                    <span class="text-muted">Client non enregistré</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong class="text-success">{{ number_format($vente->montant_total, 0, ',', ' ') }} F</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst($vente->mode_paiement) }}</span>
                                            </td>
                                            <td>
                                                @if($vente->statut == 'validee')
                                                    <span class="badge bg-success">Validée</span>
                                                @elseif($vente->statut == 'annulee')
                                                    <span class="badge bg-danger">Annulée</span>
                                                @else
                                                    <span class="badge bg-warning">En cours</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('ventes.show', $vente) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer">
                            {{ $ventes->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune vente enregistrée</h5>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Modal de suppression -->
    <div class="modal fade" id="modalSupprimer" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-trash"></i> Supprimer un utilisateur
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <form method="POST" action="{{ route('admin.utilisateur.supprimer', $user->id) }}">
                    @csrf
                    @method('DELETE')
                    
                    <div class="modal-body">
                        <!-- Avertissement -->
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-circle"></i>
                            <strong>Attention :</strong> Cette action est irréversible !
                        </div>

                        <!-- Informations de l'utilisateur -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Utilisateur à supprimer</label>
                            <p class="form-control-plaintext">
                                <strong>{{ $user->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $user->email }}</small>
                            </p>
                        </div>

                        <!-- Mot de passe requis -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-lock"></i> Confirmer avec votre mot de passe
                            </label>
                            <input type="password" 
                                   name="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Entrez votre mot de passe"
                                   required>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Confirmation -->
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="confirm" 
                                   id="confirmSupprimer"
                                   required>
                            <label class="form-check-label" for="confirmSupprimer">
                                Je confirme la suppression de cet utilisateur
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Données pour le graphique
        const ventesParMois = @json($ventesParMois);
        const labels = ventesParMois.map(v => v.mois);
        const dataVentes = ventesParMois.map(v => v.total);
        const dataCA = ventesParMois.map(v => v.ca);

        // Créer le graphique
        const ctx = document.getElementById('chartPerformance').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Nombre de ventes',
                        data: dataVentes,
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 2,
                        yAxisID: 'y',
                    },
                    {
                        label: 'CA (FCFA)',
                        data: dataCA,
                        type: 'line',
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Nombre de ventes'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'CA (FCFA)'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                    },
                }
            }
        });
    </script>
    @endpush
</x-app-layout>