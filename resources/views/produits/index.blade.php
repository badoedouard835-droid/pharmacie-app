<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                <i class="fas fa-capsules"></i> Gestion des Produits
            </h2>
            <a href="{{ route('produits.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouveau produit
            </a>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        @push('styles')
           <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        @endpush
        
        <!-- Filtres -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('produits.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   placeholder="Rechercher..." 
                                   value="{{ request('search') }}">
                        </div>
                        
                        <div class="col-md-3">
                            <select class="form-select" name="categorie">
                                <option value="">Toutes les catégories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('categorie') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <select class="form-select" name="stock">
                                <option value="">Tout stock</option>
                                <option value="ok" {{ request('stock') == 'ok' ? 'selected' : '' }}>En stock</option>
                                <option value="faible" {{ request('stock') == 'faible' ? 'selected' : '' }}>Stock faible</option>
                                <option value="rupture" {{ request('stock') == 'rupture' ? 'selected' : '' }}>Rupture</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <select class="form-select" name="expiration">
                                <option value="">Toute expiration</option>
                                <option value="proche" {{ request('expiration') == 'proche' ? 'selected' : '' }}>Expire bientôt</option>
                                <option value="perime" {{ request('expiration') == 'perime' ? 'selected' : '' }}>Périmé</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($produits->isEmpty())
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle fa-3x mb-3"></i>
                <h5>Aucun produit trouvé</h5>
                <p>Commencez par ajouter votre premier produit pharmaceutique.</p>
                <a href="{{ route('produits.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter un produit
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>Photo</th>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Prix vente</th>
                            <th>Stock</th>
                            <th>Expiration</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produits as $produit)
                            <tr>
                                <td>
                                    <img src="{{ $produit->photoUrl() }}" 
                                         alt="{{ $produit->nom }}" 
                                         class="rounded" 
                                         width="60" 
                                         height="60"
                                         style="object-fit: cover;">
                                </td>
                                <td>
                                    <strong>{{ $produit->nom }}</strong><br>
                                    <small class="text-muted">{{ $produit->code_barre }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $produit->categorie->nom }}</span>
                                </td>
                                <td>{{ number_format($produit->prix_vente, 0, ',', ' ') }} FCFA</td>
                                <td>
                                    {{ $produit->quantite_stock }}
                                    {!! $produit->badgeStock() !!}
                                </td>
                                <td>
                                    @if($produit->date_expiration)
                                        {{ $produit->date_expiration->format('d/m/Y') }}
                                        {!! $produit->badgeExpiration() !!}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($produit->statut)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-danger">Inactif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('produits.show', $produit) }}" 
                                           class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('produits.edit', $produit) }}" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" 
                                              action="{{ route('produits.destroy', $produit) }}" 
                                              class="d-inline"
                                              onsubmit="return confirm('Êtes-vous sûr ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $produits->links() }}
            </div>
        @endif

    </div>
</x-app-layout>