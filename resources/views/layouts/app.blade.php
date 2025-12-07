<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- CSS principal -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <style>
        /* ===========================================
           THÈME UTILISATEUR (Vert Moderne)
           =========================================== */
        body.user-theme {
            --primary-bg: linear-gradient(135deg, #10B981 0%, #34D399 100%);
            --primary-solid: #10B981;
            --primary-text: #1a1a1a;
            --secondary-bg: #F0FDF4;
            --card-bg: #ffffff;
            --card-text: #1a1a1a;
            --card-border: #D1FAE5;
            --btn-primary-bg: linear-gradient(135deg, #10B981 0%, #34D399 100%);
            --btn-primary-hover: linear-gradient(135deg, #059669 0%, #10B981 100%);
            --alert-success-bg: #d4edda;
            --alert-success-text: #155724;
            --alert-danger-bg: #f8d7da;
            --alert-danger-text: #721c24;
            --alert-info-bg: #d1ecf1;
            --alert-info-text: #0c5460;
            --alert-warning-bg: #fff3cd;
            --alert-warning-text: #856404;
            --table-header-bg: linear-gradient(135deg, #10B981 0%, #34D399 100%);
            --stat-card-blue: linear-gradient(135deg, #3B82F6 0%, #60A5FA 100%);
            --stat-card-green: linear-gradient(135deg, #10B981 0%, #34D399 100%);
            --stat-card-orange: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%);
            --stat-card-red: linear-gradient(135deg, #EF4444 0%, #F87171 100%);
            --footer-bg: linear-gradient(135deg, #065F46 0%, #047857 100%);
            --navbar-shadow: rgba(16, 185, 129, 0.3);
            --hover-bg: #ECFDF5;
        }

        /* ===========================================
           THÈME ADMIN (Bleu Royal Premium) - HARMONISÉ
           =========================================== */
        body.admin-theme {
            --primary-bg: linear-gradient(135deg, #2563EB 100%);
            --primary-solid: #2563EB;
            --primary-text: #1a1a1a;
            --secondary-bg: #F0F4FF;
            --card-bg: #ffffff;
            --card-text: #1a1a1a;
            --card-border: #D1E0FF;
            --btn-primary-bg: linear-gradient(135deg, #1E40AF 0%, #2563EB 100%);
            --btn-primary-hover: linear-gradient(135deg, #1E3A8A 0%, #1E40AF 100%);
            --alert-success-bg: #d4f4dd;
            --alert-success-text: #0d5f1f;
            --alert-danger-bg: #fce8e8;
            --alert-danger-text: #8b1919;
            --alert-info-bg: #E0ECFF;
            --alert-info-text: #1E40AF;
            --alert-warning-bg: #fff8e1;
            --alert-warning-text: #7f6003;
            --table-header-bg: linear-gradient(135deg, #1E40AF 0%, #2563EB 100%);
            --stat-card-blue: linear-gradient(135deg, #1E40AF 0%, #2563EB 100%);
            --stat-card-green: linear-gradient(135deg, #059669 0%, #10B981 100%);
            --stat-card-orange: linear-gradient(135deg, #D97706 0%, #F59E0B 100%);
            --stat-card-red: linear-gradient(135deg, #DC2626 0%, #EF4444 100%);
            --footer-bg: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            --navbar-shadow: rgba(30, 64, 175, 0.3);
            --badge-admin-bg: linear-gradient(135deg, #1E40AF 0%, #2563EB 100%);
            --badge-admin-text: #ffffff;
            --hover-bg: #EBF2FF;
        }

        /* ===========================================
           APPLICATION DES VARIABLES
           =========================================== */
        body {
            background-color: var(--secondary-bg);
            color: var(--primary-text);
            transition: all 0.3s ease;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Navbar */
        .navbar { 
            background: var(--primary-bg) !important;
            box-shadow: 0 4px 20px var(--navbar-shadow);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
            font-size: 1.5rem;
            color: #fff !important;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 0 0.25rem;
        }
        
        .nav-link:hover,
        .nav-link.active {
            color: #fff !important;
            background-color: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            border-radius: 12px;
        }
        
        .dropdown-item {
            padding: 0.7rem 1.5rem;
            transition: all 0.2s ease;
        }
        
        body.user-theme .dropdown-item:hover {
            background-color: #ECFDF5;
            color: var(--primary-solid);
            padding-left: 2rem;
        }
        
        body.admin-theme .dropdown-item:hover {
            background-color: #EBF2FF;
            color: var(--primary-solid);
            padding-left: 2rem;
        }

        /* Cards */
        .card { 
            background: var(--card-bg);
            color: var(--card-text);
            border: 2px solid var(--card-border);
            border-radius: 15px;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .card:hover {
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
            transform: translateY(-5px);
            border-color: var(--primary-solid);
        }
        
        .card-header {
            border-bottom: 2px solid var(--card-border);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
        }
        
        /* Stat Cards */
        .stat-card {
            border-radius: 15px;
            transition: all 0.3s ease;
            border: none;
            color: white !important;
            padding: 0.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
            transition: all 0.5s ease;
        }
        
        .stat-card:hover::before {
            top: -30%;
            right: -30%;
        }
        
        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }
        
        .stat-card.blue { background: var(--stat-card-blue); }
        .stat-card.green { background: var(--stat-card-green); }
        .stat-card.orange { background: var(--stat-card-orange); }
        .stat-card.red { background: var(--stat-card-red); }
        
        .stat-card h3, .stat-card p, .stat-card i {
            color: white !important;
            position: relative;
            z-index: 1;
        }
        
        .stat-card h3 {
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        /* Buttons */
        .btn-primary { 
            background: var(--btn-primary-bg);
            border: none;
            font-weight: 600;
            padding: 0.6rem 1.8rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            color: white;
        }
        
        .btn-primary:hover { 
            background: var(--btn-primary-hover);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }
        
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.875rem;
        }
        
        .btn-info {
            background: linear-gradient(135deg, #0891B2 0%, #06B6D4 100%);
            color: white;
            border: none;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #D97706 0%, #F59E0B 100%);
            color: white;
            border: none;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%);
            color: white;
            border: none;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #059669 0%, #10B981 100%);
            color: white;
            border: none;
        }
        
        /* Badges */
        .badge {
            padding: 0.5rem 1rem;
            font-weight: 600;
            border-radius: 8px;
            font-size: 0.85rem;
        }
        
        body.user-theme .badge.bg-primary {
            background: var(--btn-primary-bg) !important;
            color: white !important;
        }
        
        body.admin-theme .badge.bg-primary {
            background: var(--badge-admin-bg) !important;
            color: var(--badge-admin-text) !important;
        }

        /* Alerts */
        .alert { 
            border-radius: 12px;
            border: none;
            font-weight: 500;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        
        .alert-success { 
            background: var(--alert-success-bg);
            color: var(--alert-success-text);
            border-left: 5px solid #28a745;
        }
        
        .alert-danger { 
            background: var(--alert-danger-bg);
            color: var(--alert-danger-text);
            border-left: 5px solid #dc3545;
        }
        
        .alert-info { 
            background: var(--alert-info-bg);
            color: var(--alert-info-text);
            border-left: 5px solid #17a2b8;
        }
        
        .alert-warning { 
            background: var(--alert-warning-bg);
            color: var(--alert-warning-text);
            border-left: 5px solid #ffc107;
        }

        /* Tables */
        .table { 
            border-radius: 12px;
            overflow: hidden;
        }
        
        .table thead { 
            background: var(--table-header-bg);
            color: #fff;
        }
        
        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            padding: 1rem;
            border: none;
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid var(--card-border);
        }
        
        body.user-theme .table tbody tr:hover {
            background-color: #F0FDF4;
            transform: scale(1.01);
        }
        
        body.admin-theme .table tbody tr:hover {
            background-color: #F0F4FF;
            transform: scale(1.01);
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }

        /* Footer */
        footer { 
            background: var(--footer-bg);
            box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
            padding: 2rem 0;
        }
        
        footer p {
            color: rgba(255,255,255,0.95);
            margin: 0.25rem 0;
        }

        /* Form inputs */
        .form-control, .form-select {
            border: 2px solid var(--card-border);
            border-radius: 10px;
            padding: 0.7rem 1rem;
            transition: all 0.3s ease;
        }
        
        body.user-theme .form-control:focus,
        body.user-theme .form-select:focus {
            border-color: #10B981;
            box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
        }
        
        body.admin-theme .form-control:focus,
        body.admin-theme .form-select:focus {
            border-color: #1E40AF;
            box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.25);
        }

        /* Header */
        header {
            background: var(--primary-bg) !important;
            color: white !important;
            box-shadow: 0 4px 15px var(--navbar-shadow);
        }
        
        header h2, header .h4 {
            color: white !important;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
            font-weight: 700;
        }

        /* Button groups */
        .btn-group {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .btn-group .btn {
            border-radius: 0;
            border-right: 1px solid rgba(255,255,255,0.2);
        }
        
        .btn-group .btn:last-child {
            border-right: none;
        }

        /* Icons */
        .emoji-icon {
            display: inline-block;
            margin-right: 0.5rem;
            font-size: 1.1em;
        }

        /* Animations */
        @keyframes fadeIn {
            from { 
                opacity: 0;
                transform: translateY(30px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease;
        }

        /* Scrollbar personnalisée */
        body.user-theme ::-webkit-scrollbar {
            width: 10px;
        }
        
        body.user-theme ::-webkit-scrollbar-track {
            background: #F0FDF4;
        }
        
        body.user-theme ::-webkit-scrollbar-thumb {
            background: #10B981;
            border-radius: 5px;
        }
        
        body.admin-theme ::-webkit-scrollbar {
            width: 10px;
        }
        
        body.admin-theme ::-webkit-scrollbar-track {
            background: #F0F4FF;
        }
        
        body.admin-theme ::-webkit-scrollbar-thumb {
            background: #1E40AF;
            border-radius: 5px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 1rem;
            }
            
            .stat-card h3 {
                font-size: 2rem;
            }
            
            .btn-group {
                display: flex;
                flex-direction: column;
            }
            
            .btn-group .btn {
                border-radius: 8px !important;
                margin-bottom: 0.25rem;
                border-right: none;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="{{ Auth::user()->isAdmin() ? 'admin-theme' : 'user-theme' }}">
    <div class="min-vh-100 d-flex flex-column">

        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <span class="emoji-icon">💊</span> {{ config('app.name', 'Pharmacie la gloire') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <span class="emoji-icon">📊</span> Dashboard
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('produits.*') || request()->routeIs('categories.*') ? 'active' : '' }}" href="#" id="produitsDropdown" data-bs-toggle="dropdown">
                                <span class="emoji-icon">💊</span> Produits
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('produits.index') }}"><span class="emoji-icon">💊</span> Liste des produits</a></li>
                                <li><a class="dropdown-item" href="{{ route('categories.index') }}"><span class="emoji-icon">🏷️</span> Catégories</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}" href="{{ route('clients.index') }}">
                                <span class="emoji-icon">👥</span> Clients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('ventes.*') ? 'active' : '' }}" href="{{ route('ventes.index') }}">
                                <span class="emoji-icon">🛒</span> Ventes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('pharmacies.*') ? 'active' : '' }}" href="{{ route('pharmacies.index') }}">
                                <span class="emoji-icon">📍</span> Pharmacies
                            </a>
                        </li>
                    </ul>

                    <!-- USER DROPDOWN -->
                    <ul class="navbar-nav">
                        @if(Auth::check())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown">
                                @if(Auth::user()->photo)
                                    <img src="{{ Auth::user()->photoUrl() }}" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover; border: 2px solid white;" alt="Photo profil">
                                @else
                                    <div class="bg-white text-primary rounded-circle me-2 d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 14px;">
                                        {{ Auth::user()->initiales() }}
                                    </div>
                                @endif
                                <span>{{ Auth::user()->name }}</span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li class="px-3 py-2 border-bottom">
                                    <div class="small text-muted">Connecté en tant que</div>
                                    <div class="fw-bold">{{ Auth::user()->name }}</div>
                                    <div class="small mt-1"><span class="badge bg-primary">{{ ucfirst(Auth::user()->role) }}</span></div>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('profile.index') }}"><span class="emoji-icon">👤</span> Voir mon profil</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><span class="emoji-icon">✏️</span> Modifier mon profil</a></li>
                                @if(Auth::user()->isAdmin())
                                    <li><hr class="dropdown-divider"></li>
                                    <li class="px-3 py-2 bg-light">
                                        <small class="text-muted fw-bold">ADMINISTRATION</small>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('admin.utilisateurs') }}"><i class="fas fa-users"></i> Gérer les utilisateurs</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.activites') }}"><i class="fas fa-chart-line"></i> Activités & Statistiques</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.rapports') }}"><i class="fas fa-file-alt"></i> Rapports détaillés</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><span class="emoji-icon">🚪</span> Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @isset($header)
            <header class="shadow-sm">
                <div class="container-fluid py-3">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <div class="container-fluid mt-3">
            @foreach (['success','error','info','warning'] as $msg)
                @if(session($msg))
                    <div class="alert alert-{{ $msg }} alert-dismissible fade show">
                        <span class="emoji-icon">@if($msg==='success')✅@elseif($msg==='error')❌@elseif($msg==='info')ℹ️@else⚠️@endif</span> 
                        <strong>{{ ucfirst($msg) }} !</strong> {{ session($msg) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            @endforeach
        </div>

        <main class="flex-grow-1">
            @isset($slot) {{ $slot }} @endisset
            @yield('content')
        </main>

        <footer class="text-white text-center mt-auto">
            <div class="container">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Pharmacie la gloire') }}</p>
                <p class="small opacity-75">B.Y.E 👌👌👌😎😎😎✅</p>
            </div>
        </footer>

    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        // Auto-fermeture des alertes après 5 secondes
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>
</html>