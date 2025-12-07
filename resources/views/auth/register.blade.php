<x-guest-layout>
    <!-- Card d'inscription -->
    <div class="card shadow-lg">
        <div class="card-header bg-success text-white text-center py-3">
            <h4 class="mb-0">
                <i class="fas fa-user-plus"></i> Créer un compte
            </h4>
            <p class="mb-0 small">Rejoignez notre système de gestion</p>
        </div>

        <div class="card-body p-4">
            <!-- Formulaire d'inscription -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Nom complet -->
                <div class="mb-3">
                    <label for="name" class="form-label">
                        <i class="fas fa-user"></i> Nom complet <span class="text-danger">*</span>
                    </label>
                    <input 
                        id="name" 
                        type="text" 
                        class="form-control @error('name') is-invalid @enderror" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus 
                        autocomplete="name"
                        placeholder="Ex: Jean Dupont"
                    >
                    
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i> Adresse email <span class="text-danger">*</span>
                    </label>
                    <input 
                        id="email" 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="username"
                        placeholder="votre@email.com"
                    >
                    
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Téléphone (nouveau champ) -->
                <div class="mb-3">
                    <label for="telephone" class="form-label">
                        <i class="fas fa-phone"></i> Téléphone
                    </label>
                    <input 
                        id="telephone" 
                        type="tel" 
                        class="form-control @error('telephone') is-invalid @enderror" 
                        name="telephone" 
                        value="{{ old('telephone') }}" 
                        placeholder="+226 XX XX XX XX"
                    >
                    <small class="form-text text-muted">Optionnel</small>
                    
                    @error('telephone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Adresse (nouveau champ) -->
                <div class="mb-3">
                    <label for="adresse" class="form-label">
                        <i class="fas fa-map-marker-alt"></i> Adresse
                    </label>
                    <textarea 
                        id="adresse" 
                        class="form-control @error('adresse') is-invalid @enderror" 
                        name="adresse" 
                        rows="2"
                        placeholder="Votre adresse complète"
                    >{{ old('adresse') }}</textarea>
                    <small class="form-text text-muted">Optionnel</small>
                    
                    @error('adresse')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Mot de passe -->
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-key"></i> Mot de passe <span class="text-danger">*</span>
                    </label>
                    <input 
                        id="password" 
                        type="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        name="password" 
                        required 
                        autocomplete="new-password"
                        placeholder="Minimum 8 caractères"
                    >
                    <small class="form-text text-muted">Minimum 8 caractères</small>
                    
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirmation mot de passe -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">
                        <i class="fas fa-key"></i> Confirmer le mot de passe <span class="text-danger">*</span>
                    </label>
                    <input 
                        id="password_confirmation" 
                        type="password" 
                        class="form-control @error('password_confirmation') is-invalid @enderror" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        placeholder="Retapez votre mot de passe"
                    >
                    
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Boutons d'action -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <!-- Lien vers connexion -->
                    <a class="text-decoration-none small" href="{{ route('login') }}">
                        Déjà inscrit ?
                    </a>

                    <!-- Bouton d'inscription -->
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-user-plus"></i> S'inscrire
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>