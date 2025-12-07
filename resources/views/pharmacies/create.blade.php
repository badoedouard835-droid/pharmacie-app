<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-dark">
                <i class="fas fa-plus-circle"></i> Ajouter une Pharmacie
            </h2>
            <a href="{{ route('pharmacies.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="row">
                
                <!-- ============================================ -->
                <!-- COLONNE GAUCHE : FORMULAIRE -->
                <!-- ============================================ -->
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle"></i> Informations de la pharmacie
                            </h5>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('pharmacies.store') }}" enctype="multipart/form-data">
                                @csrf

                                <!-- === INFORMATIONS DE BASE === -->
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-building"></i> Informations générales
                                </h6>

                                <!-- Nom -->
                                <div class="mb-3">
                                    <label for="nom" class="form-label">
                                        Nom de la pharmacie <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" 
                                           name="nom" 
                                           value="{{ old('nom') }}" 
                                           placeholder="Ex: Pharmacie Centrale"
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
                                                  required>{{ old('adresse') }}</textarea>
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
                                               value="{{ old('ville', 'Ouagadougou') }}"
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
                                           value="{{ old('quartier') }}" 
                                           placeholder="Ex: Secteur 15">
                                    @error('quartier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- === CONTACT === -->
                                <h6 class="text-primary mb-3 mt-4">
                                    <i class="fas fa-phone"></i> Contact
                                </h6>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="telephone" class="form-label">
                                            Téléphone <span class="text-danger">*</span>
                                        </label>
                                        <input type="tel" 
                                               class="form-control @error('telephone') is-invalid @enderror" 
                                               id="telephone" 
                                               name="telephone" 
                                               value="{{ old('telephone') }}" 
                                               placeholder="+226 XX XX XX XX"
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
                                               value="{{ old('email') }}" 
                                               placeholder="contact@pharmacie.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- === COORDONNÉES GPS === -->
                                <h6 class="text-primary mb-3 mt-4">
                                    <i class="fas fa-map-marker-alt"></i> Coordonnées GPS
                                </h6>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Astuce :</strong> Cliquez sur la carte à droite pour obtenir automatiquement les coordonnées GPS.
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="latitude" class="form-label">
                                            Latitude
                                        </label>
                                        <input type="number" 
                                               class="form-control @error('latitude') is-invalid @enderror" 
                                               id="latitude" 
                                               name="latitude" 
                                               value="{{ old('latitude') }}" 
                                               step="0.00000001"
                                               placeholder="Ex: 12.3714277">
                                        <small class="form-text text-muted">Entre -90 et 90</small>
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
                                               value="{{ old('longitude') }}" 
                                               step="0.00000001"
                                               placeholder="Ex: -1.5196603">
                                        <small class="form-text text-muted">Entre -180 et 180</small>
                                        @error('longitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- === HORAIRES === -->
                                <h6 class="text-primary mb-3 mt-4">
                                    <i class="fas fa-clock"></i> Horaires
                                </h6>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="horaire_ouverture" class="form-label">
                                            Heure d'ouverture
                                        </label>
                                        <input type="time" 
                                               class="form-control @error('horaire_ouverture') is-invalid @enderror" 
                                               id="horaire_ouverture" 
                                               name="horaire_ouverture" 
                                               value="{{ old('horaire_ouverture', '08:00') }}">
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
                                               value="{{ old('horaire_fermeture', '20:00') }}">
                                        @error('horaire_fermeture')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="jours_ouverture" class="form-label">
                                            Jours d'ouverture
                                        </label>

                                        <!-- LIGNE CORRIGÉE MAIS NON MODIFIÉE -->
                                        <input type="text" 
                                               class="form-control @error('jours_ouverture') is-invalid @enderror" 
                                               id="jours_ouverture" 
                                               name="jours_ouverture" 
                                               value="{{ old('jours_ouverture', 'Lundi        et                           -Samedi') }}"
                                               placeholder="Ex: Lundi-Samedi">

                                        @error('jours_ouverture')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- === AUTRES === -->
                                <h6 class="text-primary mb-3 mt-4">
                                    <i class="fas fa-cog"></i> Autres informations
                                </h6>

                                <!-- Pharmacie de garde -->
                                <div class="mb-3 form-check">
                                    <input type="checkbox" 
                                           class="form-check-input" 
                                           id="pharmacie_garde" 
                                           name="pharmacie_garde" 
                                           value="1"
                                           {{ old('pharmacie_garde') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pharmacie_garde">
                                        <i class="fas fa-moon"></i> Cette pharmacie est actuellement de garde
                                    </label>
                                </div>

                                <!-- Photo -->
                                <div class="mb-3">
                                    <label for="photo" class="form-label">
                                        <i class="fas fa-camera"></i> Photo de la pharmacie
                                    </label>
                                    <input type="file" 
                                           class="form-control @error('photo') is-invalid @enderror" 
                                           id="photo" 
                                           name="photo" 
                                           accept="image/*">
                                    <small class="form-text text-muted">JPG, PNG (max 2 Mo)</small>
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
                                              rows="3"
                                              placeholder="Services, équipements, etc.">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Boutons -->
                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('pharmacies.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Annuler
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Enregistrer la pharmacie
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- COLONNE DROITE : CARTE POUR SÉLECTIONNER GPS -->
            <!-- ============================================ -->
            <div class="col-md-4">
                <div class="card shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-map"></i> Cliquez sur la carte
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <!-- Mini-carte pour sélectionner la position -->
                        <div id="miniMap" style="height: 400px; width: 100%;"></div>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> 
                            Cliquez sur la carte pour placer le marqueur et obtenir les coordonnées GPS automatiquement.
                        </small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- LEAFLET.JS POUR LA MINI-CARTE -->
<!-- ============================================ -->
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
    // Initialiser la mini-carte
    const miniMap = L.map('miniMap').setView([12.3714277, -1.5196603], 13);

    // Ajouter le fond de carte
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap',
        maxZoom: 19,
    }).addTo(miniMap);

    // Marqueur pour la position sélectionnée
    let marqueurSelection = null;

    // Événement : clic sur la carte
    miniMap.on('click', function(e) {
        const lat = e.latlng.lat;
        const lon = e.latlng.lng;

        // Mettre à jour les champs latitude/longitude
        document.getElementById('latitude').value = lat.toFixed(8);
        document.getElementById('longitude').value = lon.toFixed(8);

        // Supprimer l'ancien marqueur
        if (marqueurSelection) {
            miniMap.removeLayer(marqueurSelection);
        }

        // Ajouter un nouveau marqueur
        marqueurSelection = L.marker([lat, lon]).addTo(miniMap);
        marqueurSelection.bindPopup(`<strong>Position sélectionnée</strong><br>Lat: ${lat.toFixed(6)}<br>Lon: ${lon.toFixed(6)}`).openPopup();
    });

    // Si des coordonnées sont déjà renseignées (old values), afficher le marqueur
    const latInput = document.getElementById('latitude');
    const lonInput = document.getElementById('longitude');

    if (latInput.value && lonInput.value) {
        const lat = parseFloat(latInput.value);
        const lon = parseFloat(lonInput.value);
        
        marqueurSelection = L.marker([lat, lon]).addTo(miniMap);
        miniMap.setView([lat, lon], 15);
    }
</script>
@endpush
</x-app-layout>
