@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="row justify-content-center mt-5">
        <div class="col-md-10 text-center">
            <h1 class="display-4 fw-bold mb-4">
                <i class="fas fa-pills text-primary"></i>
                Bienvenue dans votre Système de Gestion de Pharmacie
            </h1>
            <p class="lead text-muted mb-5">
                Une solution complète pour gérer efficacement votre pharmacie : 
                produits, clients, ventes et géolocalisation
            </p>
            
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                Les fonctionnalités de connexion seront activées dans le Module 2
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="row mt-5 g-4">
        <div class="col-md-3">
            <div class="card text-center h-100 fade-in">
                <div class="card-body">
                    <i class="fas fa-capsules fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Gestion des Produits</h5>
                    <p class="card-text">
                        Gérez votre inventaire pharmaceutique avec suivi des stocks et alertes
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-center h-100 fade-in">
                <div class="card-body">
                    <i class="fas fa-users fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Gestion des Clients</h5>
                    <p class="card-text">
                        Suivez vos clients et leur historique d'achats facilement
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-center h-100 fade-in">
                <div class="card-body">
                    <i class="fas fa-shopping-cart fa-3x text-warning mb-3"></i>
                    <h5 class="card-title">Gestion des Ventes</h5>
                    <p class="card-text">
                        Enregistrez vos ventes et générez des factures rapidement
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-center h-100 fade-in">
                <div class="card-body">
                    <i class="fas fa-map-marker-alt fa-3x text-danger mb-3"></i>
                    <h5 class="card-title">Géolocalisation</h5>
                    <p class="card-text">
                        Localisez les pharmacies sur une carte interactive
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Installation Progress -->
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-check-circle"></i> Progression de l'installation</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <i class="fas fa-check text-success"></i> 
                            <strong>Module 1 :</strong> Configuration initiale - <span class="badge bg-success">Terminé</span>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-clock text-warning"></i> 
                            <strong>Module 2 :</strong> Authentification - <span class="badge bg-warning">En attente</span>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-clock text-secondary"></i> 
                            <strong>Module 3 :</strong> Gestion du profil - <span class="badge bg-secondary">En attente</span>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-clock text-secondary"></i> 
                            <strong>Module 4 :</strong> Gestion des produits - <span class="badge bg-secondary">En attente</span>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-clock text-secondary"></i> 
                            <strong>Module 5 :</strong> Gestion des clients - <span class="badge bg-secondary">En attente</span>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-clock text-secondary"></i> 
                            <strong>Module 6 :</strong> Gestion des ventes - <span class="badge bg-secondary">En attente</span>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-clock text-secondary"></i> 
                            <strong>Module 7 :</strong> Géolocalisation - <span class="badge bg-secondary">En attente</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection