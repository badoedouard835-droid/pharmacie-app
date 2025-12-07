<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-dark">
                <i class="fas fa-plus-circle"></i> Nouvelle Vente
            </h2>
            <a href="{{ route('ventes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <form method="POST" action="{{ route('ventes.store') }}" id="form-vente">
                @csrf

                <div class="row">
                    
                    <div class="col-md-8">
                        
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle"></i> Informations de la vente
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="alert alert-info mb-0">
                                            <strong>N° Vente :</strong> 
                                            <span class="badge bg-dark">{{ $numeroVente }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <label for="client_id" class="form-label">
                                            <i class="fas fa-user"></i> Client
                                        </label>
                                        <select class="form-select @error('client_id') is-invalid @enderror" 
                                                id="client_id" 
                                                name="client_id">
                                            <option value="">-- Nouveau client --</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                                    {{ $client->nomComplet() }} - {{ $client->telephone }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Optionnel : sélectionner un client existant ou laisser vide pour un nouveau client</small>
                                        @error('client_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div id="nouveau_client_fields" class="mt-3">
                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <label for="client_nom" class="form-label">Nom complet</label>
                                            <input type="text" class="form-control" name="client_nom" id="client_nom" placeholder="Nom du client" value="{{ old('client_nom') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="client_telephone" class="form-label">Téléphone</label>
                                            <input type="text" class="form-control" name="client_telephone" id="client_telephone" placeholder="Numéro de téléphone" value="{{ old('client_telephone') }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="client_adresse" class="form-label">Adresse</label>
                                            <input type="text" class="form-control" name="client_adresse" id="client_adresse" placeholder="Adresse complète" value="{{ old('client_adresse') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-capsules"></i> Ajouter des produits
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="produit_select" class="form-label">
                                            Produit <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select" id="produit_select">
                                            <option value="">-- Sélectionner un produit --</option>
                                            @foreach($produits as $produit)
                                                <option value="{{ $produit->id }}" 
                                                        data-nom="{{ $produit->nom }}"
                                                        data-prix="{{ $produit->prix_vente }}"
                                                        data-stock="{{ $produit->quantite_stock }}">
                                                    {{ $produit->nom }} - {{ number_format($produit->prix_vente,0,',',' ') }} FCFA 
                                                    (Stock: {{ $produit->quantite_stock }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="quantite_input" class="form-label">
                                            Quantité <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" 
                                               class="form-control" 
                                               id="quantite_input" 
                                               min="1" 
                                               value="1">
                                    </div>

                                    <div class="col-md-3 d-flex align-items-end">
                                        <button type="button" 
                                                class="btn btn-success w-100" 
                                                id="btn_ajouter_produit">
                                            <i class="fas fa-plus"></i> Ajouter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm">
                            <div class="card-header bg-warning">
                                <h5 class="mb-0">
                                    <i class="fas fa-shopping-cart"></i> Panier 
                                    <span class="badge bg-dark" id="badge_nb_produits">0</span>
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0" id="table_panier">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Produit</th>
                                                <th width="120">Prix Unit.</th>
                                                <th width="100">Qté</th>
                                                <th width="120">Remise</th>
                                                <th width="150">Total</th>
                                                <th width="80"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody_panier"></tbody>
                                    </table>
                                </div>

                                <div id="panier_vide" class="text-center py-5">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Votre panier est vide. Ajoutez des produits ci-dessus.</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4">
                        
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-calculator"></i> Récapitulatif
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Sous-total :</span>
                                    <strong id="montant_sous_total">0 FCFA</strong>
                                </div>

                                <div class="mb-3">
                                    <label for="remise" class="form-label">
                                        Remise globale
                                    </label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="remise" 
                                           name="remise" 
                                           min="0" 
                                           step="0.01"
                                           value="0">
                                    <small class="form-text text-muted">En FCFA</small>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between mb-2">
                                    <span>TVA (18%) :</span>
                                    <strong id="montant_tva">0 FCFA</strong>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between">
                                    <h5>Total TTC :</h5>
                                    <h4 class="text-success" id="montant_total">0 FCFA</h4>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                Mode de paiement
                            </div>
                            <div class="card-body">
                                <select class="form-select" name="mode_paiement" required>
                                    <option value="">-- Sélectionner --</option>
                                    <option value="especes">Espèces</option>
                                    <option value="carte">Carte bancaire</option>
                                    <option value="mobile_money">Mobile Money</option>
                                    <option value="cheque">Chèque</option>
                                </select>
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-secondary text-white">
                                Remarques
                            </div>
                            <div class="card-body">
                                <textarea class="form-control" 
                                          name="remarques" 
                                          rows="3"
                                          placeholder="Notes supplémentaires..."></textarea>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" 
                                    class="btn btn-success btn-lg" 
                                    id="btn_valider_vente"
                                    disabled>
                                <i class="fas fa-check"></i> Valider la vente
                            </button>
                        </div>

                    </div>

                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        let panier = [];

        const clientSelect = document.getElementById('client_id');
        const nouveauClientFields = document.getElementById('nouveau_client_fields');

        clientSelect.addEventListener('change', () => {
            nouveauClientFields.style.display = clientSelect.value ? 'none' : 'block';
        });
        clientSelect.dispatchEvent(new Event('change'));

        document.getElementById('btn_ajouter_produit').addEventListener('click', function() {
            const selectProduit = document.getElementById('produit_select');
            const quantite = parseInt(document.getElementById('quantite_input').value);

            if (!selectProduit.value) return alert('Veuillez sélectionner un produit');
            if (quantite < 1) return alert('La quantité doit être supérieure à 0');

            const option = selectProduit.options[selectProduit.selectedIndex];
            const produitId = selectProduit.value;
            const nom = option.dataset.nom;
            const prix = parseFloat(option.dataset.prix);
            const stock = parseInt(option.dataset.stock);

            if (quantite > stock)
                return alert(`Stock insuffisant ! Stock disponible : ${stock}`);

            const indexExistant = panier.findIndex(p => p.id === produitId);

            if (indexExistant !== -1) {
                panier[indexExistant].quantite += quantite;
            } else {
                panier.push({
                    id: produitId,
                    nom,
                    prix,
                    quantite,
                    remise: 0,
                    stock
                });
            }

            selectProduit.value = '';
            document.getElementById('quantite_input').value = 1;

            rafraichirPanier();
        });

        function rafraichirPanier() {
            const tbody = document.getElementById('tbody_panier');
            const badge = document.getElementById('badge_nb_produits');
            const panierVide = document.getElementById('panier_vide');
            const btnValider = document.getElementById('btn_valider_vente');

            tbody.innerHTML = '';

            if (panier.length === 0) {
                panierVide.style.display = 'block';
                badge.textContent = 0;
                btnValider.disabled = true;
                calculerTotaux();
                return;
            }

            panierVide.style.display = 'none';
            badge.textContent = panier.length;
            btnValider.disabled = false;

            panier.forEach((p, index) => {
                const total = (p.prix * p.quantite) - p.remise;

                tbody.innerHTML += `
                    <tr>
                        <td>${p.nom}</td>
                        <td>${formatPrix(p.prix)}</td>
                        <td>
                            <input type="number" min="1" max="${p.stock}"
                                   value="${p.quantite}"
                                   class="form-control form-control-sm"
                                   onchange="changerQuantite(${index}, this.value)">
                        </td>
                        <td>
                            <input type="number" min="0"
                                   value="${p.remise}"
                                   class="form-control form-control-sm"
                                   onchange="changerRemise(${index}, this.value)">
                        </td>
                        <td>${formatPrix(total)}</td>
                        <td>
                            <button type="button"
                                    class="btn btn-danger btn-sm"
                                    onclick="supprimerProduit(${index})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
            });

            calculerTotaux();
        }

        function changerQuantite(index, valeur) {
            valeur = parseInt(valeur);

            if (valeur < 1) return rafraichirPanier();
            if (valeur > panier[index].stock) {
                alert(`Stock max : ${panier[index].stock}`);
                return rafraichirPanier();
            }

            panier[index].quantite = valeur;
            rafraichirPanier();
        }

        function changerRemise(index, valeur) {
            valeur = parseFloat(valeur);
            if (valeur < 0) valeur = 0;

            panier[index].remise = valeur;
            rafraichirPanier();
        }

        function supprimerProduit(index) {
            panier.splice(index, 1);
            rafraichirPanier();
        }

        function calculerTotaux() {
            let sousTotal = 0;

            panier.forEach(p => {
                sousTotal += (p.prix * p.quantite) - p.remise;
            });

            const remiseGlobale = parseFloat(document.getElementById('remise').value || 0);
            const tva = 0.18 * (sousTotal - remiseGlobale);
            const total = (sousTotal - remiseGlobale) + tva;

            document.getElementById('montant_sous_total').textContent = formatPrix(sousTotal);
            document.getElementById('montant_tva').textContent = formatPrix(tva);
            document.getElementById('montant_total').textContent = formatPrix(total);
        }

        document.getElementById('remise').addEventListener('input', calculerTotaux);

        function formatPrix(valeur) {
            return new Intl.NumberFormat('fr-FR').format(valeur) + ' FCFA';
        }

        // ✅ Soumission du formulaire
        document.getElementById('form-vente').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (panier.length === 0) {
                alert('Veuillez ajouter au moins un produit au panier');
                return;
            }

            panier.forEach((item, index) => {
                const fields = [
                    { name: `produits[${index}][produit_id]`, value: item.id },
                    { name: `produits[${index}][quantite]`, value: item.quantite },
                    { name: `produits[${index}][prix_unitaire]`, value: item.prix },
                    { name: `produits[${index}][remise]`, value: item.remise }
                ];

                fields.forEach(field => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = field.name;
                    input.value = field.value;
                    this.appendChild(input);
                });
            });

            this.submit();
        });

    </script>
    @endpush

</x-app-layout>
