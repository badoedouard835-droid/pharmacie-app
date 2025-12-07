<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Créer des utilisateurs de test
     */
    public function run(): void
    {
        // ============================================
        // ADMINISTRATEUR
        // ============================================
        User::create([
            'name' => 'Administrateur Système',
            'email' => 'admin@pharmacie.com',
            'password' => Hash::make('admin123'),
            'telephone' => '+226 70 00 00 00',
            'adresse' => 'Ouagadougou, Burkina Faso',
            'role' => 'admin',
            'statut' => true,
        ]);

        // ============================================
        // PHARMACIEN
        // ============================================
        User::create([
            'name' => 'Marie Kaboré',
            'email' => 'marie@pharmacie.com',
            'password' => Hash::make('pharmacien123'),
            'telephone' => '+226 71 11 11 11',
            'adresse' => 'Bobo-Dioulasso, Burkina Faso',
            'role' => 'pharmacien',
            'statut' => true,
        ]);

        // ============================================
        // VENDEUR
        // ============================================
        User::create([
            'name' => 'Pierre Ouédraogo',
            'email' => 'vendeur@pharmacie.com',
            'password' => Hash::make('vendeur123'),
            'telephone' => '+226 72 22 22 22',
            'adresse' => 'Koudougou, Burkina Faso',
            'role' => 'vendeur',
            'statut' => true,
        ]);

        $this->command->info('✅ 3 utilisateurs de test créés :');
        $this->command->info('   - admin@pharmacie.com / admin123 (Admin)');
        $this->command->info('   - marie@pharmacie.com / pharmacien123 (Pharmacien)');
        $this->command->info('   - vendeur@pharmacie.com / vendeur123 (Vendeur)');
    }
}