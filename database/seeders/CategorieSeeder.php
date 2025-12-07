<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categorie;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'nom' => 'Antibiotiques',
                'description' => 'Médicaments pour combattre les infections bactériennes',
            ],
            [
                'nom' => 'Antalgiques',
                'description' => 'Médicaments contre la douleur',
            ],
            [
                'nom' => 'Vitamines',
                'description' => 'Compléments vitaminiques et nutritionnels',
            ],
            [
                'nom' => 'Antipaludiques',
                'description' => 'Traitement et prévention du paludisme',
            ],
            [
                'nom' => 'Anti-inflammatoires',
                'description' => 'Médicaments contre l\'inflammation',
            ],
            [
                'nom' => 'Antihistaminiques',
                'description' => 'Traitement des allergies',
            ],
            [
                'nom' => 'Sirops',
                'description' => 'Sirops médicamenteux divers',
            ],
            [
                'nom' => 'Pommades et crèmes',
                'description' => 'Applications topiques',
            ],
        ];

        foreach ($categories as $categorie) {
            Categorie::create($categorie);
        }
    }
}