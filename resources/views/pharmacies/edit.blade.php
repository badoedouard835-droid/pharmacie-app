<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-dark">
                <i class="fas fa-edit"></i> Modifier la Pharmacie
            </h2>
            <div>
                <a href="{{ route('pharmacies.show', $pharmacie) }}" class="btn btn-info btn-sm me-2">
                    <i class="fas fa-eye"></i> Voir
                </a>
                <a href="{{ route('pharmacies.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="row">
                
                <!-- FORMULAIRE (identique à create, mais avec les valeurs existantes) -->
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning">
                            <h5 class="mb-0">
                                <i class="fas fa-edit"></i> Modification : {{ $pharmacie->nom }}
                            </h5>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('pharmacies.update', $pharmacie) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Nom -->
                                <div class="mb-3">
                                    <label for="nom" class="form-label">
                                        Nom de la pharmacie <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" 
                                           name="nom" 
                                           value="{{ old('nom', $pharmacie->nom) }}" 
                                           required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Adresse -->
                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <label for="adresse" class="form-label">
                                            Adresse <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control @error('adresse') is-invalid @enderror" 
                                                  id="adresse" 
                                                  name="adresse" 
                                                  rows="2"
                                                  required>{{ old('adresse', $pharmacie->adresse) }}</textarea>
                                        @error('adresse')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="ville" class="form-label">
                                            Ville <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('ville') is-invalid @enderror" 
                                               id="ville" 
                                               name="ville" 
                                               value="{{ old('ville', $pharmacie->ville) }}"
                                               required>
                                        @error('ville')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Quartier -->
                                <div class="mb-3">
                                    <label for="quartier" class="form-label">
                                        Quartier / Secteur
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('quartier') is-invalid @enderror" 
                                           id="quartier" 
                                           name="quartier" 
                                           value="{{ old('quartier', $pharmacie->quartier) }}">
                                    @error('quartier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Contact -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="telephone" class="form-label">
                                            Téléphone <span class="text-danger">*</span>
                                        </label>
                                        <input type="tel" 
                                               class="form-control @error('telephone') is-invalid @enderror" 
                                               id="telephone" 
                                               name="telephone" 
                                               value="{{ old('telephone', $pharmacie->telephone) }}" 
                                               required>
                                        @error('telephone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">
                                            Email
                                        </label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', $pharmacie->email) }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- GPS -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="latitude" class="form-label">
                                            Latitude
                                        </label>
                                        <input type="number" 
                                               class="form-control @error('latitude') is-invalid @enderror" 
                                               id="latitude" 
                                               name="latitude" 
                                               value="{{ old('latitude', $pharmacie->latitude) }}" 
                                               step="0.00000001">
                                        @error('latitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="longitude" class="form-label">
                                            Longitude
                                        </label>
                                        <input type="number" 
                                               class="form-control @error('longitude') is-invalid @enderror" 
                                               id="longitude" 
                                               name="longitude" 
                                               value="{{ old('longitude', $pharmacie->longitude) }}" 
                                               step="0.00000001">
                                        @error('longitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Horaires -->
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="horaire_ouverture" class="form-label">
                                            Heure d'ouverture
                                        </label>
                                        <input type="time" 
                                               class="form-control @error('horaire_ouverture') is-invalid @enderror" 
                                               id="horaire_ouverture" 
                                               name="horaire_ouverture" 
                                               value="{{ old('horaire_ouverture', $pharmacie->horaire_ouverture ? $pharmacie->horaire_ouverture->format('H:i') : '') }}">
                                        @error('horaire_ouverture')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="horaire_fermeture" class="form-label">
                                            Heure de fermeture
                                        </label>
                                        <input type="time" 
                                               class="form-control @error('horaire_fermeture') is-invalid @enderror" 
                                               id="horaire_fermeture" 
                                               name="horaire_fermeture" 
                                               value="{{ old('horaire_fermeture', $pharmacie->horaire_fermeture ? $pharmacie->horaire_fermeture->format('H:i') : '') }}">
                                        @error('horaire_fermeture')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="jours_ouverture" class="form-label">
                                            Jours d'ouverture
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('jours_ouverture') is-invalid @enderror" 
                                               id="jours_ouverture" 
                                               name="jours_ouverture" 
                                               value="{{ old('jours_ouverture', $pharmacie->jours_ouverture) }}">
                                        @error('jours_ouverture')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Pharmacie de garde -->
                                <div class="mb-3 form-check">
                                    <input type="checkbox" 
                                           class="form-check-input" 
                                           id="pharmacie_garde" 
                                           name="pharmacie_garde" 
                                           value="1"
                                           {{ old('pharmacie_garde', $pharmacie->pharmacie_garde) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pharmacie_garde">
                                        <i class="fas fa-moon"></i> Cette pharmacie est actuellement de garde
                                    </label>
                                </div>

                                <!-- Photo actuelle -->
                                @if($pharmacie->photo)
                                    <div class="mb-3">
                                        <label class="form-label">Photo actuelle</label>
                                        <div>
                                            <img src="{{ $pharmacie->photoUrl() }}" 
                                                 alt="Photo" 
                                                 class="img-thumbnail" 
                                                 style="max-height: 150px;">
                                        </div>
                                    </div>
                                @endif

                                <!-- Nouvelle photo -->
                                <div class="mb-3">
                                    <label for="photo" class="form-label">
                                        <i class="fas fa-camera"></i> {{ $pharmacie->photo ? 'Changer la photo' : 'Ajouter une photo' }}
                                    </label>
                                    <input type="file" 
                                           class="form-control @error('photo') is-invalid @enderror" 
                                           id="photo" 
                                           name="photo" 
                                           accept="image/*">
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="mb-3">
                                    <label for="description" class="form-label">
                                        <i class="fas fa-comment"></i> Description
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="3">{{ old('description', $pharmacie->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Boutons -->
                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('pharmacies.show', $pharmacie) }}" class="btn btn-secondary">
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

                <!-- Mini-carte (même code que create) -->
                <div class="col-md-4">
                    <div class="card shadow-sm sticky-top" style="top: 20px;">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-map"></i> Cliquez sur la carte
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div id="miniMap" style="height: 400px; width: 100%;"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

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
        // Mini-carte (même code que create.blade.php)
        const lat = {{ $pharmacie->latitude ?? 12.3714277 }};
        const lon = {{ $pharmacie->longitude ?? -1.5196603 }};
        
        const miniMap = L.map('miniMap').setView([lat, lon], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap',
            maxZoom: 19,
        }).addTo(miniMap);

        let marqueurSelection = null;

        // Si coordonnées existantes, afficher le marqueur
        @if($pharmacie->hasCoordinates())
            marqueurSelection = L.marker([lat, lon]).addTo(miniMap);
        @endif

        miniMap.on('click', function(e) {
            const lat = e.latlng.lat;
            const lon = e.latlng.lng;

            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lon.toFixed(8);

            if (marqueurSelection) {
                miniMap.removeLayer(marqueurSelection);
            }

            marqueurSelection = L.marker([lat, lon]).addTo(miniMap);
            marqueurSelection.bindPopup(`Lat: ${lat.toFixed(6)}<br>Lon: ${lon.toFixed(6)}`).openPopup();
        });
    </script>
    @endpush

</x-app-layout>