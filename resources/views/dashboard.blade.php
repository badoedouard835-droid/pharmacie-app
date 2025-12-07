<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-dark">
            📊 Tableau de bord
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">

            <!-- ALERT BIENVENUE -->
            <div class="alert alert-success mb-4" style="border-left: 4px solid #4caf50; background: rgba(76, 175, 80, 0.1);">
                <span style="font-size: 1.2rem; margin-right: 0.8rem;">✓</span>
                <strong>Bienvenue {{ Auth::user()->name }} !</strong>
                Vous êtes connecté en tant que 
                <span class="badge bg-primary">{{ ucfirst(Auth::user()->role) }}</span>
            </div>

            @if(Auth::user()->role === 'admin')
                <!-- ====================== DASHBOARD ADMINISTRATEUR ====================== -->

                <!-- STATISTIQUES PRINCIPALES -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <a href="{{ route('produits.index') }}" style="text-decoration: none;">
                            <div class="card" style="border-top: 4px solid #667eea;">
                                <div class="card-body text-center">
                                    <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📦</div>
                                    <div style="color: #999; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Produits</div>
                                    <div style="font-size: 2rem; font-weight: bold; color: #333;">{{ \App\Models\Produit::count() }}</div>
                                    <div style="color: #4caf50; font-size: 0.85rem; margin-top: 0.5rem;">↑ Tous les articles</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-3">
                        <a href="{{ route('clients.index') }}" style="text-decoration: none;">
                            <div class="card" style="border-top: 4px solid #4caf50;">
                                <div class="card-body text-center">
                                    <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">👥</div>
                                    <div style="color: #999; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Clients</div>
                                    <div style="font-size: 2rem; font-weight: bold; color: #333;">{{ \App\Models\Client::count() }}</div>
                                    <div style="color: #4caf50; font-size: 0.85rem; margin-top: 0.5rem;">↑ Actifs</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-3">
                        <a href="{{ route('ventes.index') }}" style="text-decoration: none;">
                            <div class="card" style="border-top: 4px solid #ff9800;">
                                <div class="card-body text-center">
                                    <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🛒</div>
                                    <div style="color: #999; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Ventes</div>
                                    <div style="font-size: 2rem; font-weight: bold; color: #333;">{{ \App\Models\Vente::count() }}</div>
                                    <div style="color: #4caf50; font-size: 0.85rem; margin-top: 0.5rem;">↑ Totales</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-3">
                        <a href="{{ route('ventes.index') }}" style="text-decoration: none;">
                            <div class="card" style="border-top: 4px solid #f44336;">
                                <div class="card-body text-center">
                                    <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">💰</div>
                                    <div style="color: #999; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Revenus</div>
                                    <div style="font-size: 2rem; font-weight: bold; color: #333;">{{ number_format(\App\Models\Vente::sum('montant_total'), 0, ',', ' ') }} FCFA</div>
                                    <div style="color: #4caf50; font-size: 0.85rem; margin-top: 0.5rem;">↑ Total</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- GRILLE PRINCIPALE -->
                <div class="row">
                    <!-- ACTIONS RAPIDES -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header" style="padding-bottom: 1rem; border-bottom: 2px solid #f5f5f5;">
                                <h5 style="margin: 0; font-weight: bold; color: #333;">⚡ Actions rapides</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <a href="{{ route('produits.create') }}" 
                                           style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 1.2rem; border-radius: 12px; text-decoration: none; display: flex; flex-direction: column; gap: 0.8rem; font-size: 1rem; transition: all 0.3s;"
                                           onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 10px 30px rgba(102, 126, 234, 0.3)';"
                                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                            <span style="font-size: 1.8rem;">➕</span>
                                            <span style="font-weight: 600;">Ajouter un produit</span>
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('categories.create') }}" 
                                           style="background: linear-gradient(135deg, #f093fb, #f5576c); color: white; border: none; padding: 1.2rem; border-radius: 12px; text-decoration: none; display: flex; flex-direction: column; gap: 0.8rem; font-size: 1rem; transition: all 0.3s;"
                                           onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 10px 30px rgba(245, 87, 108, 0.3)';"
                                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                            <span style="font-size: 1.8rem;">📂</span>
                                            <span style="font-weight: 600;">Catégorie</span>
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('clients.create') }}" 
                                           style="background: linear-gradient(135deg, #4facfe, #00f2fe); color: #333; border: none; padding: 1.2rem; border-radius: 12px; text-decoration: none; display: flex; flex-direction: column; gap: 0.8rem; font-size: 1rem; transition: all 0.3s;"
                                           onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 10px 30px rgba(79, 172, 254, 0.3)';"
                                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                            <span style="font-size: 1.8rem;">👤</span>
                                            <span style="font-weight: 600;">Nouveau client</span>
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('ventes.create') }}" 
                                           style="background: linear-gradient(135deg, #30cfd0, #330867); color: white; border: none; padding: 1.2rem; border-radius: 12px; text-decoration: none; display: flex; flex-direction: column; gap: 0.8rem; font-size: 1rem; transition: all 0.3s;"
                                           onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 10px 30px rgba(48, 207, 208, 0.3)';"
                                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                            <span style="font-size: 1.8rem;">💳</span>
                                            <span style="font-weight: 600;">Nouvelle vente</span>
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('pharmacies.index') }}" 
                                           style="background: linear-gradient(135deg, #fa709a, #fee140); color: #333; border: none; padding: 1.2rem; border-radius: 12px; text-decoration: none; display: flex; flex-direction: column; gap: 0.8rem; font-size: 1rem; transition: all 0.3s;"
                                           onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 10px 30px rgba(250, 112, 154, 0.3)';"
                                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                            <span style="font-size: 1.8rem;">🗺️</span>
                                            <span style="font-weight: 600;">Pharmacies</span>
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('admin.utilisateurs') }}" 
                                           style="background: linear-gradient(135deg, #a8edea, #fed6e3); color: #333; border: none; padding: 1.2rem; border-radius: 12px; text-decoration: none; display: flex; flex-direction: column; gap: 0.8rem; font-size: 1rem; transition: all 0.3s;"
                                           onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 10px 30px rgba(168, 237, 234, 0.3)';"
                                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                            <span style="font-size: 1.8rem;">⚙️</span>
                                            <span style="font-weight: 600;">Utilisateurs</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PROFIL UTILISATEUR -->
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem; margin: 0 auto 1.5rem; box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);">
                                    {{ substr(Auth::user()->name, 0, 1) }}{{ substr(Auth::user()->name, strpos(Auth::user()->name, ' ') + 1, 1) }}
                                </div>
                                <h4 style="margin-bottom: 0.5rem; color: #333;">{{ Auth::user()->name }}</h4>
                                <div style="display: inline-block; background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; margin-bottom: 1.5rem;">
                                    ADMINISTRATEUR
                                </div>
                                
                                <div style="text-align: left; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #f0f0f0;">
                                    <div style="display: flex; justify-content: space-between; padding: 0.8rem 0; font-size: 0.95rem;">
                                        <span style="color: #999;">📧 Email</span>
                                        <span style="font-weight: 600; color: #333;">{{ Auth::user()->email }}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; padding: 0.8rem 0; font-size: 0.95rem;">
                                        <span style="color: #999;">📱 Téléphone</span>
                                        <span style="font-weight: 600; color: #333;">{{ Auth::user()->telephone ?? 'N/A' }}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; padding: 0.8rem 0; font-size: 0.95rem;">
                                        <span style="color: #999;">📅 Depuis</span>
                                        <span style="font-weight: 600; color: #333;">{{ Auth::user()->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- ====================== DASHBOARD UTILISATEUR NORMAL ====================== -->

                <!-- STATISTIQUES SIMPLIFIÉES -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <a href="{{ route('clients.index') }}" style="text-decoration: none;">
                            <div class="card" style="border-top: 4px solid #667eea;">
                                <div class="card-body text-center">
                                    <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">👥</div>
                                    <div style="color: #999; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Mes Clients</div>
                                    <div style="font-size: 2rem; font-weight: bold; color: #333;">{{ \App\Models\Client::count() }}</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="{{ route('ventes.index') }}" style="text-decoration: none;">
                            <div class="card" style="border-top: 4px solid #4caf50;">
                                <div class="card-body text-center">
                                    <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🛒</div>
                                    <div style="color: #999; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Mes Ventes</div>
                                    <div style="font-size: 2rem; font-weight: bold; color: #333;">{{ \App\Models\Vente::where('user_id', Auth::user()->id)->count() }}</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-4">
                        <div class="card" style="border-top: 4px solid #f44336;">
                            <div class="card-body text-center">
                                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">💰</div>
                                <div style="color: #999; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Mon Chiffre</div>
                                <div style="font-size: 2rem; font-weight: bold; color: #333;">{{ number_format(\App\Models\Vente::where('user_id', Auth::user()->id)->sum('montant_total'), 0, ',', ' ') }} FCFA</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CONTENU PRINCIPAL -->
                <div class="row">
                    <!-- ACTIONS RAPIDES -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header" style="padding-bottom: 1rem; border-bottom: 2px solid #f5f5f5;">
                                <h5 style="margin: 0; font-weight: bold; color: #333;">⚡ Actions rapides</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <a href="{{ route('clients.create') }}" 
                                           style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 1.2rem; border-radius: 12px; text-decoration: none; display: flex; flex-direction: column; gap: 0.8rem; font-size: 1rem; transition: all 0.3s;"
                                           onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 10px 30px rgba(102, 126, 234, 0.3)';"
                                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                            <span style="font-size: 1.8rem;">👤</span>
                                            <span style="font-weight: 600;">Nouveau client</span>
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('ventes.create') }}" 
                                           style="background: linear-gradient(135deg, #4caf50, #45a049); color: white; border: none; padding: 1.2rem; border-radius: 12px; text-decoration: none; display: flex; flex-direction: column; gap: 0.8rem; font-size: 1rem; transition: all 0.3s;"
                                           onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 10px 30px rgba(76, 175, 80, 0.3)';"
                                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                            <span style="font-size: 1.8rem;">💳</span>
                                            <span style="font-weight: 600;">Nouvelle vente</span>
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('clients.index') }}" 
                                           style="background: linear-gradient(135deg, #ff9800, #f57c00); color: white; border: none; padding: 1.2rem; border-radius: 12px; text-decoration: none; display: flex; flex-direction: column; gap: 0.8rem; font-size: 1rem; transition: all 0.3s;"
                                           onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 10px 30px rgba(255, 152, 0, 0.3)';"
                                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                            <span style="font-size: 1.8rem;">📋</span>
                                            <span style="font-weight: 600;">Mes clients</span>
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('ventes.index') }}" 
                                           style="background: linear-gradient(135deg, #2196f3, #1976d2); color: white; border: none; padding: 1.2rem; border-radius: 12px; text-decoration: none; display: flex; flex-direction: column; gap: 0.8rem; font-size: 1rem; transition: all 0.3s;"
                                           onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 10px 30px rgba(33, 150, 243, 0.3)';"
                                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                            <span style="font-size: 1.8rem;">📊</span>
                                            <span style="font-weight: 600;">Mes ventes</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PROFIL UTILISATEUR -->
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem; margin: 0 auto 1.5rem; box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);">
                                    {{ substr(Auth::user()->name, 0, 1) }}{{ substr(Auth::user()->name, strpos(Auth::user()->name, ' ') + 1, 1) ?? '' }}
                                </div>
                                <h4 style="margin-bottom: 0.5rem; color: #333;">{{ Auth::user()->name }}</h4>
                                <div style="display: inline-block; background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; margin-bottom: 1.5rem;">
                                    {{ ucfirst(Auth::user()->role) }}
                                </div>
                                
                                <div style="text-align: left; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #f0f0f0;">
                                    <div style="display: flex; justify-content: space-between; padding: 0.8rem 0; font-size: 0.95rem;">
                                        <span style="color: #999;">📧 Email</span>
                                        <span style="font-weight: 600; color: #333;">{{ Auth::user()->email }}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; padding: 0.8rem 0; font-size: 0.95rem;">
                                        <span style="color: #999;">📱 Téléphone</span>
                                        <span style="font-weight: 600; color: #333;">{{ Auth::user()->telephone ?? 'N/A' }}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; padding: 0.8rem 0; font-size: 0.95rem;">
                                        <span style="color: #999;">📅 Depuis</span>
                                        <span style="font-weight: 600; color: #333;">{{ Auth::user()->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endif

        </div>
    </div>

</x-app-layout>
