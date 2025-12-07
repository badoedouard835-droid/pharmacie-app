<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * ============================================
     * ORDRE CORRECT DES SEEDERS
     * ============================================
     * 1. Users (car les ventes ont besoin d'un user_id)
     * 2. Categories (car les produits ont besoin d'une categorie_id)
     * 3. Produits (car les ventes ont besoin de produits)
     * 4. Clients (peuvent être créés indépendamment)
     * 5. Ventes (optionnel - nécessitent users, clients et produits)
     */
    public function run(): void
    {
        // Ordre important !
        $this->call([
            UserSeeder::class,        // ✅ D'ABORD les utilisateurs
            CategorieSeeder::class,   // ✅ PUIS les catégories
            ProduitSeeder::class,     // ✅ PUIS les produits (dépendent des catégories)
            ClientSeeder::class,      // ✅ PUIS les clients
            PharmacieSeeder::class,   // ✅ PUIS les pharmacies
        ]);
        
        $this->command->info('');
        $this->command->info('🎉 TOUTES LES DONNÉES ONT ÉTÉ CRÉÉES AVEC SUCCÈS !');
        $this->command->info('');
        $this->command->info('📊 Résumé :');
        $this->command->info('   ✅ ' . \App\Models\User::count() . ' utilisateurs');
        $this->command->info('   ✅ ' . \App\Models\Categorie::count() . ' catégories');
        $this->command->info('   ✅ ' . \App\Models\Produit::count() . ' produits');
        $this->command->info('   ✅ ' . \App\Models\Client::count() . ' clients');
        $this->command->info('');
        $this->command->info('🔐 Comptes de connexion :');
        $this->command->info('   Admin       : admin@pharmacie.com / admin123');
        $this->command->info('   Pharmacien  : marie@pharmacie.com / pharmacien123');
        $this->command->info('   Vendeur     : vendeur@pharmacie.com / vendeur123');
    }
}