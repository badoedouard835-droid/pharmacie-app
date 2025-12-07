<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-white">
                <i class="fas fa-chart-line"></i> Activités & Statistiques
            </h2>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            
            <!-- Sélecteur de période -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label fw-bold"><i class="fas fa-calendar"></i> Période d'analyse</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="periode" id="jour" value="jour" {{ $periode == 'jour' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary" for="jour">Aujourd'hui</label>
                                
                                <input type="radio" class="btn-check" name="periode" id="semaine" value="semaine" {{ $periode == 'semaine' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary" for="semaine">Cette semaine</label>
                                
                                <input type="radio" class="btn-check" name="periode" id="mois" value="mois" {{ $periode == 'mois' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary" for="mois">Ce mois</label>
                                
                                <input type="radio" class="btn-check" name="periode" id="annee" value="annee" {{ $periode == 'annee' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary" for="annee">Cette année</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-sync"></i> Actualiser
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistiques globales -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card stat-card blue text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                            <h3 class="mb-0">{{ $stats['ventes_total'] }}</h3>
                            <p class="mb-0">Ventes totales</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card green text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-money-bill-wave fa-3x mb-3"></i>
                            <h3 class="mb-0">{{ number_format($stats['ca_total'], 0, ',', ' ') }}</h3>
                            <p class="mb-0">CA (FCFA)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card orange text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-boxes fa-3x mb-3"></i>
                            <h3 class="mb-0">{{ $stats['produits_vendus'] }}</h3>
                            <p class="mb-0">Produits vendus</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card red text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-user-plus fa-3x mb-3"></i>
                            <h3 class="mb-0">{{ $stats['nouveaux_clients'] }}</h3>
                            <p class="mb-0">Nouveaux clients</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Top Vendeurs -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-trophy"></i> Top 5 Vendeurs</h5>
                        </div>
                        <div class="card-body">
                            @if($topVendeurs->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Rang</th>
                                                <th>Vendeur</th>
                                                <th class="text-center">Ventes</th>
                                                <th class="text-end">CA (FCFA)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($topVendeurs as $index => $vendeur)
                                                <tr>
                                                    <td>
                                                        @if($index == 0)
                                                            <span class="badge bg-warning text-dark">🥇</span>
                                                        @elseif($index == 1)
                                                            <span class="badge bg-secondary">🥈</span>
                                                        @elseif($index == 2)
                                                            <span class="badge bg-danger">🥉</span>
                                                        @else
                                                            <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.utilisateur.profil', $vendeur) }}" class="text-decoration-none">
                                                            <strong>{{ $vendeur->name }}</strong>
                                                        </a>
                                                        <br>
                                                        <small class="text-muted">{{ ucfirst($vendeur->role) }}</small>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-info">{{ $vendeur->total_ventes }}</span>
                                                    </td>
                                                    <td class="text-end">
                                                        <strong class="text-success">{{ number_format($vendeur->ca_total ?? 0, 0, ',', ' ') }}</strong>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-center text-muted py-4">Aucune donnée disponible</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Top Produits -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-star"></i> Top 10 Produits</h5>
                        </div>
                        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                            @if($topProduits->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Produit</th>
                                                <th class="text-center">Qté</th>
                                                <th class="text-end">CA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($topProduits as $produit)
                                                <tr>
                                                    <td>
                                                        <strong>{{ Str::limit($produit->nom, 30) }}</strong>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-primary">{{ $produit->quantite_vendue }}</span>
                                                    </td>
                                                    <td class="text-end">
                                                        <small class="text-success fw-bold">{{ number_format($produit->ca_produit, 0, ',', ' ') }}</small>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-center text-muted py-4">Aucune donnée disponible</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphique des ventes -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-area"></i> Évolution des ventes</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartVentes" height="80"></canvas>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Préparer les données
        const ventesData = @json($ventesParJour);
        const labels = ventesData.map(v => v.date);
        const dataVentes = ventesData.map(v => v.total);
        const dataCA = ventesData.map(v => v.ca);

        // Créer le graphique
        const ctx = document.getElementById('chartVentes').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Nombre de ventes',
                        data: dataVentes,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Chiffre d\'affaires (FCFA)',
                        data: dataCA,
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