<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                <i class="fas fa-tag"></i> Catégorie : {{ $categorie->nom }}
            </h2>
            <div>
                <a href="{{ route('categories.edit', $categorie) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $categorie->nom }}</h5>
                        <p class="card-text">{{ $categorie->description ?? 'Pas de description' }}</p>
                        <span class="badge bg-primary">{{ $produits->total() }} produit(s)</span>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="mb-3">Produits dans cette catégorie</h5>

        @if($produits->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Aucun produit dans cette catégorie.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Photo</th>
                            <th>Nom</th>
                            <th>Prix vente</th>
                            <th>Stock</th>
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
                                         width="50" 
                                         height="50"
                                         style="object-fit: cover;">
                                </td>
                                <td>{{ $produit->nom }}</td>
                                <td>{{ number_format($produit->prix_vente, 0, ',', ' ') }} FCFA</td>
                                <td>{{ $produit->quantite_stock }}</td>
                                <td>{!! $produit->badgeStock() !!}</td>
                                <td>
                                    <a href="{{ route('produits.show', $produit) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $produits->links() }}
            </div>
        @endif

    </div>
</x-app-layout>