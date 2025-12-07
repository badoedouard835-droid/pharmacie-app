<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-dark">
                <i class="fas fa-building"></i> {{ $pharmacie->nom }}
            </h2>
            <div>
                <a href="{{ route('pharmacies.edit', $pharmacie) }}" class="btn btn-warning btn-sm me-2">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <a href="{{ route('pharmacies.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <div class="row">
                
                <!-- ============================================ -->
                <!-- COLONNE GAUCHE : INFORMATIONS -->
                <!-- ============================================ -->
                <div class="col-md-4">
                    
                    <!-- Card Photo et Statut -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body text-center">
                            <!-- Photo -->
                            @if($pharmacie->photo)
                                <img src="{{ $pharmacie->photoUrl() }}" 
                                     alt="Photo de la pharmacie" 
                                     class="img-fluid rounded mb-3"
                                     style="max-height: 250px; object-fit: cover;">
                            @else
                                <div class="bg-light p-5 rounded mb-3">
                                    <i class="fas fa-building fa-5x text-muted"></i>
                                </div>
                            @endif

                            <!-- Nom et statut -->
                            <h4 class="mb-2">{{ $pharmacie->nom }}</h4>
                            
                            @if($pharmacie->pharmacie_garde)
                                <span class="badge bg-warning text-dark mb-2">
                                    <i class="fas fa-moon"></i> Pharmacie de garde
                                </span>
                            @endif

                            @if($pharmacie->estOuverte())
                                <span class="badge bg-success mb-2">
                                    <i class="fas fa-check-circle"></i> Ouverte
                                </span>
                            @else
                                <span class="badge bg-danger mb-2">
                                    <i class="fas fa-times-circle"></i> Fermée
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Informations de contact -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-info-circle"></i> Informations
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Adresse -->
                            <div class="mb-3">
                                <strong><i class="fas fa-map-marker-alt text-primary"></i> Adresse</strong>
                                <p class="mb-0">{{ $pharmacie->adresse }}</p>
                                @if($pharmacie->quartier)
                                    <small class="text-muted">{{ $pharmacie->quartier }}</small><br>
                                @endif
                                <small class="text-muted">{{ $pharmacie->ville }}</small>
                            </div>

                            <!-- Téléphone -->
                            <div class="mb-3">
                                <strong><i class="fas fa-phone text-primary"></i> Téléphone</strong>
                                <p class="mb-0">
                                    <a href="tel:{{ $pharmacie->telephone }}" class="text-decoration-none">
                                        {{ $pharmacie->telephone }}
                                    </a>
                                </p>
                            </div>

                            <!-- Email -->
                            @if($pharmacie->email)
                                <div class="mb-3">
                                    <strong><i class="fas fa-envelope text-primary"></i> Email</strong>
                                    <p class="mb-0">
                                        <a href="mailto:{{ $pharmacie->email }}" class="text-decoration-none">
                                            {{ $pharmacie->email }}
                                        </a>
                                    </p>
                                </div>
                            @endif

                            <!-- Horaires -->
                            <div class="mb-3">
                                <strong><i class="fas fa-clock text-primary"></i> Horaires</strong>
                                <p class="mb-0">{{ $pharmacie->horairesFormats() }}</p>
                                @if($pharmacie->jours_ouverture)
                                    <small class="text-muted">{{ $pharmacie->jours_ouverture }}</small>
                                @endif
                            </div>

                            <!-- Coordonnées GPS -->
                            @if($pharmacie->hasCoordinates())
                                <div class="mb-0">
                                    <strong><i class="fas fa-map-pin text-primary"></i> GPS</strong>
                                    <p class="mb-0">
                                        <small class="font-monospace">
                                            {{ $pharmacie->latitude }}, {{ $pharmacie->longitude }}
                                        </small>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-bolt"></i> Actions rapides
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <!-- Appeler -->
                                <a href="tel:{{ $pharmacie->telephone }}" class="btn btn-primary">
                                    <i class="fas fa-phone"></i> Appeler
                                </a>

                                <!-- Itinéraire Google Maps -->
                                @if($pharmacie->hasCoordinates())
                                    <a href="{{ $pharmacie->lienGoogleMaps() }}" 
                                       target="_blank" 
                                       class="btn btn-success">
                                        <i class="fas fa-directions"></i> Itinéraire
                                    </a>
                                @endif

                                <!-- Email -->
                                @if($pharmacie->email)
                                    <a href="mailto:{{ $pharmacie->email }}" class="btn btn-info">
                                        <i class="fas fa-envelope"></i> Envoyer un email
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ============================================ -->
                <!-- COLONNE DROITE : CARTE + DESCRIPTION -->
                <!-- ============================================ -->
                <div class="col-md-8">
                    
                    <!-- Card Carte -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-map"></i> Localisation
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            @if($pharmacie->hasCoordinates())
                                <!-- Carte Leaflet -->
                                <div id="map" style="height: 400px; width: 100%;"></div>
                            @else
                                <!-- Pas de coordonnées -->
                                <div class="text-center py-5">
                                    <i class="fas fa-map-marker-alt fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">Coordonnées GPS non renseignées</h5>
                                    <p class="text-muted">Modifiez la pharmacie pour ajouter sa position.</p>
                                    <a href="{{ route('pharmacies.edit', $pharmacie) }}" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> Ajouter la position
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card Description -->
                    @if($pharmacie->description)
                        <div class="card shadow-sm">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-comment"></i> Description
                                </h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $pharmacie->description }}</p>
                            </div>
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- LEAFLET.JS POUR LA CARTE -->
    <!-- ============================================ -->
    @if($pharmacie->hasCoordinates())
        @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
              integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
              crossorigin="" />
        @endpush

        @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
                integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
                crossorigin=""></script>

        <script>
            // Initialiser la carte centrée sur la pharmacie
            const map = L.map('map').setView([{{ $pharmacie->latitude }}, {{ $pharmacie->longitude }}], 16);

            // Ajouter le fond de carte
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap',
                maxZoom: 19,
            }).addTo(map);

            // Ajouter un marqueur
            const icone = L.divIcon({
                className: 'custom-marker',
                html: '<div style="background-color: green; width: 40px; height: 40px; border-radius: 50%; border: 4px solid white; box-shadow: 0 2px 10px rgba(0,0,0,0.4);"></div>',
                iconSize: [40, 40],
                iconAnchor: [20, 20],
            });

            const marqueur = L.marker([{{ $pharmacie->latitude }}, {{ $pharmacie->longitude }}], {
                icon: icone
            }).addTo(map);

            // Popup
            marqueur.bindPopup(`
                <div style="min-width: 200px;">
                    <h6><strong>{{ $pharmacie->nom }}</strong></h6>
                    <p class="mb-1 small"><i class="fas fa-map-marker-alt"></i> {{ $pharmacie->adresse }}</p>
                    <p class="mb-0 small"><i class="fas fa-phone"></i> {{ $pharmacie->telephone }}</p>
                </div>
            `).openPopup();
        </script>
        @endpush
    @endif

</x-app-layout>