<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <!-- Bootstrap CSS avec fallback -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
      rel="stylesheet" 
      integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" 
      crossorigin="anonymous"
      onerror="this.onerror=null; this.href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css'">
    
    <!-- Font Awesome via CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-light">
    <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-5">
        <!-- Logo / Titre de l'application -->
        <div class="text-center mb-4">
            <a href="/" class="text-decoration-none">
                <h2 class="text-primary fw-bold">
                    <i class="fas fa-pills"></i> Pharmacie la gloire
                </h2>
            </a>
        </div>

        <!-- Contenu de la page (formulaire de connexion/inscription) -->
        <div class="w-100" style="max-width: 500px;">
            {{ $slot }}
        </div>
    </div>

    <!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" 
        crossorigin="anonymous"
        onerror="this.onerror=null; this.src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js'"></script>
</body>
</html>