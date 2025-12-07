<x-guest-layout>
    <!-- Card de connexion -->
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center py-3">
            <h4 class="mb-0">
                <i class="fas fa-lock"></i> Connexion
            </h4>
            <p class="mb-0 small">Accédez à votre espace de gestion</p>
        </div>

        <div class="card-body p-4">
            <!-- Message de session (si déconnexion réussie par exemple) -->
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i> {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Formulaire de connexion -->
            <form method="POST" action="{{ route('login') }}">
                @csrf
                {{-- 
                    @csrf : Token de sécurité Laravel
                    Protection contre les attaques CSRF (Cross-Site Request Forgery)
                --}}

                <!-- Champ Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i> Adresse email
                    </label>
                    <input 
                        id="email" 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        autocomplete="username"
                        placeholder="votre@email.com"
                    >
                    {{-- 
                        old('email') : Garde la valeur si le formulaire a des erreurs
                        @error('email') : Ajoute la classe 'is-invalid' si erreur
                    --}}
                    
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Champ Mot de passe -->
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-key"></i> Mot de passe
                    </label>
                    <input 
                        id="password" 
                        type="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        placeholder="••••••••"
                    >
                    
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Case à cocher "Se souvenir de moi" -->
                <div class="mb-3 form-check">
                    <input 
                        id="remember_me" 
                        type="checkbox" 
                        class="form-check-input" 
                        name="remember"
                    >
                    <label class="form-check-label" for="remember_me">
                        Se souvenir de moi
                    </label>
                </div>

                <!-- Boutons d'action -->
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Lien "Mot de passe oublié" -->
                    @if (Route::has('password.request'))
                        <a class="text-decoration-none small" href="{{ route('password.request') }}">
                            Mot de passe oublié ?
                        </a>
                    @endif

                    <!-- Bouton de connexion -->
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Se connecter
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer de la card : lien vers inscription -->
        <div class="card-footer text-center bg-light">
            <p class="mb-0 text-muted">
                Pas encore de compte ? 
                <a href="{{ route('register') }}" class="text-decoration-none fw-bold">
                    Créer un compte
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>