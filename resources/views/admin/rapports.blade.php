<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-white">
                <i class="fas fa-file-alt"></i> Rapports Détaillés
            </h2>
            <button onclick="window.print()" class="btn btn-light">
                <i class="fas fa-print"></i> Imprimer
            </button>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            
            <!-- Filtres -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold"><i class="fas fa-list"></i> Type de rapport</label>
                            <select name="type" class="form-select">
                                <option value="ventes" {{ $type == 'ventes' ? 'selected' : '' }}>Ventes</option>
                                <option value="produits" {{ $type == 'produits' ? 'selected' : '' }}>Produits</option>
                                <option value="clients" {{ $type == 'clients' ? 'selected' : '' }}>Clients</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold"><i class="fas fa-calendar"></i> Date début</label>
                            <input type="date" name="date_debut" class="form-control" value="{{ $dateDebut }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold"><i class="fas fa-calendar"></i> Date fin</label>
                            <input type="date" name="date_fin" class="form-control" value="{{ $dateFin }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Générer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($type == 'ventes')
                <!-- Rapport Ventes -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card blue text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                <h3>{{ $rapportData['stats']['total_ventes'] }}</h3>
                                <p class="mb-0">Ventes totales</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card green text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                                <h3>{{ number_format($rapportData['stats']['ca_total'], 0, ',', ' ') }}</h3>
                                <p class="mb-0">CA Total</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card orange text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-line fa-2x mb-2"></i>
                                <h3>{{ number_format($rapportData['stats']['ca_moyen'], 0, ',', ' ') }}</h3>
                                <p class="mb-0">CA Moyen</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card red text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-ban fa-2x mb-2"></i>
                                <h3>{{ $rapportData['stats']['ventes_annulees'] }}</h3>
                                <p class="mb-0">Annulées</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Répartition par mode de paiement -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-credit-card"></i> Répartition par paiement</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="chartPaiements" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Détails</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Mode</th>
                                            <th class="text-center">Nombre</th>
                                            <th class="text-end">Montant</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rapportData['paiements'] as $mode => $data)
                                            <tr>
                                                <td><strong>{{ ucfirst($mode) }}</strong></td>
                                                <td class="text-center">{{ $data['count'] }}</td>
                                                <td class="text-end">{{ number_format($data['total'], 0, ',', ' ') }} FCFA</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($type == 'produits')
                <!-- Rapport Produits -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card blue text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-boxes fa-2x mb-2"></i>
                                <h3>{{ $rapportData['stats']['total_produits'] }}</h3>
                                <p class="mb-0">Total produits</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card green text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <h3>{{ $rapportData['stats']['produits_vendus'] }}</h3>
                                <p class="mb-0">Produits vendus</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card orange text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-shopping-basket fa-2x mb-2"></i>
                                <h3>{{ $rapportData['stats']['quantite_totale'] }}</h3>
                                <p class="mb-0">Quantité vendue</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card red text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                                <h3>{{ number_format($rapportData['stats']['ca_total'], 0, ',', ' ') }}</h3>
                                <p class="mb-0">CA Total</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Détail par produit</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produit</th>
                                        <th class="text-center">Qté vendue</th>
                                        <th class="text-center">Nb ventes</th>
                                        <th class="text-end">CA (FCFA)</th>
                                        <th class="text-center">Stock actuel</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rapportData['produits'] as $produit)
                                        <tr>
                                            <td><strong>{{ $produit->nom }}</strong></td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">{{ $produit->quantite_vendue ?? 0 }}</span>
                                            </td>
                                            <td class="text-center">{{ $produit->nombre_ventes ?? 0 }}</td>
                                            <td class="text-end">
                                                <strong class="text-success">{{ number_format($produit->ca_produit ?? 0, 0, ',', ' ') }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge {{ $produit->quantite_stock < 10 ? 'bg-danger' : 'bg-success' }}">
                                                    {{ $produit->quantite_stock }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            @elseif($type == 'clients')
                <!-- Rapport Clients -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card blue text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <h3>{{ $rapportData['stats']['total_clients'] }}</h3>
                                <p class="mb-0">Total clients</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card green text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-user-check fa-2x mb-2"></i>
                                <h3>{{ $rapportData['stats']['clients_actifs'] }}</h3>
                                <p class="mb-0">Clients actifs</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card orange text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                                <h3>{{ number_format($rapportData['stats']['ca_total'], 0, ',', ' ') }}</h3>
                                <p class="mb-0">CA Total</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card red text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-line fa-2x mb-2"></i>
                                <h3>{{ number_format($rapportData['stats']['ca_moyen'], 0, ',', ' ') }}</h3>
                                <p class="mb-0">CA Moyen</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Détail par client</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Client</th>
                                        <th>Téléphone</th>
                                        <th class="text-center">Achats</th>
                                        <th class="text-end">CA (FCFA)</th>
                                        <th>Dernière visite</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rapportData['clients'] as $client)
                                        <tr>
                                            <td>
                                                <strong>{{ $client->nomComplet() }}</strong>
                                                @if($client->type == 'professionnel')
                                                    <span class="badge bg-success ms-1">Pro</span>
                                                @endif
                                            </td>
                                            <td>{{ $client->telephone }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">{{ $client->total_achats ?? 0 }}</span>
                                            </td>
                                            <td class="text-end">
                                                <strong class="text-success">{{ number_format($client->ca_client ?? 0, 0, ',', ' ') }}</strong>
                                            </td>
                                            <td>
                                                @if($client->derniere_visite)
                                                    <small>{{ \Carbon\Carbon::parse($client->derniere_visite)->format('d/m/Y') }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    @if($type == 'ventes' && isset($rapportData['paiements']))
        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const paiements = @json($rapportData['paiements']);
            const labels = Object.keys(paiements);
            const data = Object.values(paiements).map(p => p.total);
            
            const ctx = document.getElementById('chartPaiements').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels.map(l => l.charAt(0).toUpperCase() + l.slice(1)),
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(251, 191, 36, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        </script>
        @endpush
    @endif
</x-app-layout>