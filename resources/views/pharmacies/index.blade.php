<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-dark">
                <i class="fas fa-map-marked-alt"></i> Carte des Pharmacies
            </h2>
            <a href="{{ route('pharmacies.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter une pharmacie
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            @push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush
            
            <!-- ============================================ -->
            <!-- STATISTIQUES RAPIDES -->
            <!-- ============================================ -->
            <div class="row g-3 mb-4">
                <!-- Total pharmacies -->
                <div class="col-md-4">
                    <div class="card stat-card blue text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-building fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                            <p class="mb-0">Pharmacies Enregistrées</p>
                        </div>
                    </div>
                </div>

                <!-- Avec GPS -->
                <div class="col-md-4">
                    <div class="card stat-card green text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $stats['avec_gps'] }}</h3>
                            <p class="mb-0">Avec Géolocalisation</p>
                        </div>
                    </div>
                </div>

                <!-- De garde -->
                <div class="col-md-4">
                    <div class="card stat-card orange text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-moon fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $stats['de_garde'] }}</h3>
                            <p class="mb-0">Pharmacies de Garde</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- CARTE INTERACTIVE -->
            <!-- ============================================ -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-0">
                                <i class="fas fa-map"></i> Carte Interactive
                            </h5>
                        </div>
                        <div class="col-md-4">
                            <button type="button" 
                                    class="btn btn-light btn-sm float-end" 
                                    id="btn_ma_position">
                                <i class="fas fa-location-arrow"></i> Ma position
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Div pour la carte Leaflet -->
                    <div id="map" style="height: 600px; width: 100%;"></div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-3">
                            <span class="badge bg-success me-2">●</span> Pharmacie ouverte
                        </div>
                        <div class="col-md-3">
                            <span class="badge bg-danger me-2">●</span> Pharmacie fermée
                        </div>
                        <div class="col-md-3">
                            <span class="badge bg-warning me-2">●</span> Pharmacie de garde
                        </div>
                        <div class="col-md-3">
                            <span class="badge bg-primary me-2">●</span> Ma position
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- LISTE DES PHARMACIES -->
            <!-- ============================================ -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Liste des Pharmacies
                    </h5>
                </div>

                <div class="card-body p-0">
                    @if($pharmacies->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Adresse</th>
                                        <th>Téléphone</th>
                                        <th>Horaires</th>
                                        <th>Statut</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pharmacies as $pharmacie)
                                        <tr>
                                            <!-- Nom -->
                                            <td>
                                                <strong>{{ $pharmacie->nom }}</strong>
                                                @if($pharmacie->pharmacie_garde)
                                                    <span class="badge bg-warning text-dark ms-2">
                                                        <i class="fas fa-moon"></i> De garde
                                                    </span>
                                                @endif
                                            </td>

                                            <!-- Adresse -->
                                            <td>
                                                <small>
                                                    {{ $pharmacie->adresse }}<br>
                                                    <span class="text-muted">{{ $pharmacie->ville }}</span>
                                                </small>
                                            </td>

                                            <!-- Téléphone -->
                                            <td>
                                                <i class="fas fa-phone text-primary"></i>
                                                {{ $pharmacie->telephone }}
                                            </td>

                                            <!-- Horaires -->
                                            <td>
                                                <small>{{ $pharmacie->horairesFormats() }}</small>
                                            </td>

                                            <!-- Statut -->
                                            <td>
                                                @if($pharmacie->estOuverte())
                                                    <span class="badge bg-success">Ouverte</span>
                                                @elseif($pharmacie->pharmacie_garde)
                                                    <span class="badge bg-warning text-dark">De garde</span>
                                                @else
                                                    <span class="badge bg-danger">Fermée</span>
                                                @endif
                                            </td>

                                            <!-- Actions -->
                                            <td class="text-end">
                                                <div class="btn-group" role="group">
                                                    <!-- Voir sur la carte -->
                                                    <button type="button" 
                                                            class="btn btn-sm btn-primary" 
                                                            onclick="centrerSurPharmacie({{ $pharmacie->latitude }}, {{ $pharmacie->longitude }})"
                                                            title="Voir sur la carte">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                    </button>

                                                    <!-- Voir détails -->
                                                    <a href="{{ route('pharmacies.show', $pharmacie) }}" 
                                                       class="btn btn-sm btn-info" 
                                                       title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <!-- Modifier -->
                                                    <a href="{{ route('pharmacies.edit', $pharmacie) }}" 
                                                       class="btn btn-sm btn-warning" 
                                                       title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <!-- Supprimer -->
                                                    <form method="POST" 
                                                          action="{{ route('pharmacies.destroy', $pharmacie) }}" 
                                                          style="display:inline;"
                                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette pharmacie ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-danger" 
                                                                title="Supprimer">
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
                    @else
                        <!-- Aucune pharmacie -->
                        <div class="text-center py-5">
                            <i class="fas fa-map-marked-alt fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune pharmacie enregistrée</h5>
                            <p class="text-muted">Commencez par ajouter votre première pharmacie !</p>
                            <a href="{{ route('pharmacies.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Ajouter une pharmacie
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- ============================================ -->
    <!-- INCLURE LEAFLET.JS (CSS + JS) -->
    <!-- ============================================ -->
    @push('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
          crossorigin="" />
    @endpush

    @push('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
            crossorigin=""></script>

    <script>
        // ============================================
        // INITIALISATION DE LA CARTE
        // ============================================
        
        // Coordonnées de Ouagadougou (centre par défaut)
        const centreOuaga = [12.3714277, -1.5196603];
        
        // Créer la carte
        const map = L.map('map').setView(centreOuaga, 13);
        
        // Ajouter le fond de carte OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19,
        }).addTo(map);

        // ============================================
        // VARIABLES GLOBALES
        // ============================================
        let marqueurPosition = null; // Marqueur de la position de l'utilisateur
        let cercleRayon = null; // Cercle du rayon de recherche

        // ============================================
        // AJOUTER LES PHARMACIES SUR LA CARTE
        // ============================================
        const pharmacies = @json($pharmacies); // Convertir les données PHP en JSON

        pharmacies.forEach(pharmacie => {
            if (pharmacie.latitude && pharmacie.longitude) {
                
                // Choisir la couleur du marqueur selon le statut
                let couleur = 'red'; // Fermée par défaut
                
                if (pharmacie.pharmacie_garde) {
                    couleur = 'orange'; // De garde
                } else {
                    // Vérifier si ouverte (simplification - on vérifie juste l'horaire)
                    const maintenant = new Date();
                    const heureActuelle = maintenant.getHours() * 60 + maintenant.getMinutes();
                    
                    if (pharmacie.horaire_ouverture && pharmacie.horaire_fermeture) {
                        const [hO, mO] = pharmacie.horaire_ouverture.split(':');
                        const [hF, mF] = pharmacie.horaire_fermeture.split(':');
                        const ouverture = parseInt(hO) * 60 + parseInt(mO);
                        const fermeture = parseInt(hF) * 60 + parseInt(mF);
                        
                        if (heureActuelle >= ouverture && heureActuelle <= fermeture) {
                            couleur = 'green'; // Ouverte
                        }
                    }
                }

                // Créer une icône personnalisée
                const icone = L.divIcon({
                    className: 'custom-marker',
                    html: `<div style="background-color: ${couleur}; width: 30px; height: 30px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>`,
                    iconSize: [30, 30],
                    iconAnchor: [15, 15],
                });

                // Créer le marqueur
                const marqueur = L.marker([pharmacie.latitude, pharmacie.longitude], {
                    icon: icone
                }).addTo(map);

                // Créer le popup
                const statutTexte = pharmacie.pharmacie_garde ? 
                    '<span class="badge bg-warning text-dark">De garde</span>' :
                    (couleur === 'green' ? '<span class="badge bg-success">Ouverte</span>' : '<span class="badge bg-danger">Fermée</span>');

                const popupContent = `
                    <div style="min-width: 200px;">
                        <h6 class="mb-2"><strong>${pharmacie.nom}</strong></h6>
                        <p class="mb-1 small"><i class="fas fa-map-marker-alt"></i> ${pharmacie.adresse}</p>
                        <p class="mb-1 small"><i class="fas fa-phone"></i> ${pharmacie.telephone}</p>
                        <p class="mb-2 small"><i class="fas fa-clock"></i> ${pharmacie.horaire_ouverture || 'N/A'} - ${pharmacie.horaire_fermeture || 'N/A'}</p>
                        <p class="mb-2">${statutTexte}</p>
                        <div class="d-flex gap-2">
                            <a href="/pharmacies/${pharmacie.id}" class="btn btn-sm btn-primary">
                                <i class="fas fa-info-circle"></i> Détails
                            </a>
                            <a href="${pharmacie.latitude},${pharmacie.longitude}" 
                               target="_blank" 
                               class="btn btn-sm btn-success">
                                <i class="fas fa-directions"></i> Itinéraire
                            </a>
                        </div>
                    </div>
                `;

                marqueur.bindPopup(popupContent);
            }
        });

        // ============================================
        // FONCTION : CENTRER SUR UNE PHARMACIE
        // ============================================
        function centrerSurPharmacie(lat, lon) {
            map.setView([lat, lon], 16); // Zoom 16 = très proche
            
            // Trouver le marqueur et ouvrir son popup
            map.eachLayer(layer => {
                if (layer instanceof L.Marker) {
                    const pos = layer.getLatLng();
                    if (pos.lat === lat && pos.lng === lon) {
                        layer.openPopup();
                    }
                }
            });
        }

        // ============================================
        // BOUTON : MA POSITION
        // ============================================
        document.getElementById('btn_ma_position').addEventListener('click', function() {
            if (!navigator.geolocation) {
                alert('La géolocalisation n\'est pas supportée par votre navigateur');
                return;
            }

            // Afficher un loader
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Localisation...';
            this.disabled = true;

            navigator.geolocation.getCurrentPosition(
                // Succès
                (position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    // Supprimer l'ancien marqueur s'il existe
                    if (marqueurPosition) {
                        map.removeLayer(marqueurPosition);
                    }
                    if (cercleRayon) {
                        map.removeLayer(cercleRayon);
                    }

                    // Créer un marqueur bleu pour la position
                    const iconePosition = L.divIcon({
                        className: 'custom-marker',
                        html: '<div style="background-color: blue; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>',
                        iconSize: [20, 20],
                        iconAnchor: [10, 10],
                    });

                    marqueurPosition = L.marker([lat, lon], {
                        icon: iconePosition
                    }).addTo(map);

                    marqueurPosition.bindPopup('<strong>Vous êtes ici</strong>').openPopup();

                    // Ajouter un cercle de 5km de rayon
                    cercleRayon = L.circle([lat, lon], {
                        color: 'blue',
                        fillColor: '#3388ff',
                        fillOpacity: 0.1,
                        radius: 5000 // 5 km
                    }).addTo(map);

                    // Centrer la carte sur la position
                    map.setView([lat, lon], 14);

                    // Rechercher les pharmacies à proximité (AJAX)
                    rechercherProximite(lat, lon);

                    // Restaurer le bouton
                    this.innerHTML = '<i class="fas fa-location-arrow"></i> Ma position';
                    this.disabled = false;
                },
                // Erreur
                (error) => {
                    alert('Impossible de récupérer votre position : ' + error.message);
                    this.innerHTML = '<i class="fas fa-location-arrow"></i> Ma position';
                    this.disabled = false;
                }
            );
        });

        // ============================================
        // FONCTION : RECHERCHER À PROXIMITÉ
        // ============================================
        function rechercherProximite(lat, lon) {
            fetch(`/pharmacies-proximite?latitude=${lat}&longitude=${lon}&rayon=10`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log(`${data.total} pharmacie(s) trouvée(s) dans un rayon de 10 km`);
                        
                        // Vous pouvez afficher une notification
                        if (data.total > 0) {
                            // Créer une notification Bootstrap
                            const notification = document.createElement('div');
                            notification.className = 'alert alert-info alert-dismissible fade show position-fixed';
                            notification.style.cssText = 'top: 80px; right: 20px; z-index: 9999; min-width: 300px;';
                            notification.innerHTML = `
                                <i class="fas fa-map-marker-alt"></i> 
                                <strong>${data.total} pharmacie(s)</strong> trouvée(s) à proximité
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            `;
                            document.body.appendChild(notification);

                            // Auto-supprimer après 5 secondes
                            setTimeout(() => notification.remove(), 5000);
                        }
                    }
                })
                .catch(error => console.error('Erreur:', error));
        }
    </script>
    @endpush

</x-app-layout>