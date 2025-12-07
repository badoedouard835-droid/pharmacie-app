<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                <i class="fas fa-tags"></i> Gestion des Catégories
            </h2>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvelle catégorie
            </a>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        @push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush
        
        @if($categories->isEmpty())
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle fa-3x mb-3"></i>
                <h5>Aucune catégorie trouvée</h5>
                <p>Commencez par créer votre première catégorie de produits.</p>
                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer une catégorie
                </a>
            </div>
        @else
            <div class="row">
                @foreach($categories as $categorie)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-tag text-primary"></i> {{ $categorie->nom }}
                                </h5>
                                <p class="card-text text-muted">
                                    {{ $categorie->description ?? 'Pas de description' }}
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="badge bg-primary">
                                        {{ $categorie->produits_count }} produit(s)
                                    </span>
                                    
                                    <div class="btn-group">
                                        <a href="{{ route('categories.show', $categorie) }}" 
                                           class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('categories.edit', $categorie) }}" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" 
                                              action="{{ route('categories.destroy', $categorie) }}" 
                                              class="d-inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $categories->links() }}
            </div>
        @endif

    </div>
</x-app-layout>