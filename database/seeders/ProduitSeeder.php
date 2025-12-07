<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produit;
use App\Models\Categorie;
use Carbon\Carbon;

class ProduitSeeder extends Seeder
{
    public function run(): void
    {
        $antibiotiques = Categorie::where('nom', 'Antibiotiques')->first()->id;
        $antalgiques = Categorie::where('nom', 'Antalgiques')->first()->id;
        $vitamines = Categorie::where('nom', 'Vitamines')->first()->id;
        $antipaludiques = Categorie::where('nom', 'Antipaludiques')->first()->id;

        $produits = [
            [
                'code_barre' => '3400936390034',
                'nom' => 'Paracétamol 500mg',
                'description' => 'Antalgique et antipyrétique',
                'categorie_id' => $antalgiques,
                'prix_achat' => 150,
                'prix_vente' => 250,
                'quantite_stock' => 500,
                'stock_minimum' => 50,
                'date_expiration' => Carbon::now()->addMonths(18),
                'laboratoire' => 'Sanofi',
                'forme' => 'Comprimé',
                'dosage' => '500mg',
                'statut' => true,
            ],
            [
                'code_barre' => '3400938559347',
                'nom' => 'Amoxicilline 1g',
                'description' => 'Antibiotique à large spectre',
                'categorie_id' => $antibiotiques,
                'prix_achat' => 800,
                'prix_vente' => 1200,
                'quantite_stock' => 200,
                'stock_minimum' => 30,
                'date_expiration' => Carbon::now()->addYear(),
                'laboratoire' => 'Pfizer',
                'forme' => 'Comprimé',
                'dosage' => '1g',
                'statut' => true,
            ],
            [
                'code_barre' => '3400935696526',
                'nom' => 'Vitamine C 1000mg',
                'description' => 'Complément vitaminique',
                'categorie_id' => $vitamines,
                'prix_achat' => 300,
                'prix_vente' => 500,
                'quantite_stock' => 150,
                'stock_minimum' => 20,
                'date_expiration' => Carbon::now()->addMonths(24),
                'laboratoire' => 'Bayer',
                'forme' => 'Comprimé effervescent',
                'dosage' => '1000mg',
                'statut' => true,
            ],
            [
                'code_barre' => '3400930334355',
                'nom' => 'Coartem',
                'description' => 'Traitement du paludisme',
                'categorie_id' => $antipaludiques,
                'prix_achat' => 1500,
                'prix_vente' => 2500,
                'quantite_stock' => 80,
                'stock_minimum' => 20,
                'date_expiration' => Carbon::now()->addMonths(15),
                'laboratoire' => 'Novartis',
                'forme' => 'Comprimé',
                'dosage' => '20/120mg',
                'statut' => true,
            ],
            [
                'code_barre' => '3400937459389',
                'nom' => 'Ibuprofène 400mg',
                'description' => 'Anti-inflammatoire non stéroïdien',
                'categorie_id' => $antalgiques,
                'prix_achat' => 200,
                'prix_vente' => 350,
                'quantite_stock' => 3, // Stock faible pour tester l'alerte
                'stock_minimum' => 40,
                'date_expiration' => Carbon::now()->addMonths(20),
                'laboratoire' => 'Advil',
                'forme' => 'Comprimé',
                'dosage' => '400mg',
                'statut' => true,
            ],
            [
                'code_barre' => '3400938762549',
                'nom' => 'Doliprane 1000mg',
                'description' => 'Paracétamol pour adultes',
                'categorie_id' => $antalgiques,
                'prix_achat' => 180,
                'prix_vente' => 300,
                'quantite_stock' => 0, // Rupture de stock pour tester
                'stock_minimum' => 50,
                'date_expiration' => Carbon::now()->addMonths(12),
                'laboratoire' => 'Sanofi',
                'forme' => 'Comprimé',
                'dosage' => '1000mg',
                'statut' => true,
            ],
            [
                'code_barre' => '3400945567432',
                'nom' => 'Augmentin 500mg',
                'description' => 'Antibiotique amoxicilline + acide clavulanique',
                'categorie_id' => $antibiotiques,
                'prix_achat' => 1200,
                'prix_vente' => 1800,
                'quantite_stock' => 120,
                'stock_minimum' => 25,
                'date_expiration' => Carbon::now()->addDays(20), // Expire bientôt
                'laboratoire' => 'GSK',
                'forme' => 'Comprimé',
                'dosage' => '500mg/125mg',
                'statut' => true,
            ],
        ];

        foreach ($produits as $produit) {
            Produit::create($produit);
        }
    }
}