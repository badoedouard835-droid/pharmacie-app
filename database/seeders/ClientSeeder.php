<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * ============================================
     * REMPLIR LA BASE AVEC DES CLIENTS DE TEST
     * ============================================
     * Exécuter avec : php artisan db:seed --class=ClientSeeder
     */
    public function run(): void
    {
        // Tableau de clients de test
        $clients = [
            [
                'numero_client' => 'CLI001',
                'nom' => 'OUEDRAOGO',
                'prenom' => 'Jean',
                'telephone' => '+226 70 11 22 33',
                'email' => 'jean.ouedraogo@email.com',
                'adresse' => 'Secteur 15, Ouagadougou',
                'ville' => 'Ouagadougou',
                'date_naissance' => '1985-05-15',
                'sexe' => 'M',
                'type' => 'particulier',
                'remarques' => 'Client régulier, préfère les achats le matin',
            ],
            [
                'numero_client' => 'CLI002',
                'nom' => 'KABORE',
                'prenom' => 'Marie',
                'telephone' => '+226 71 22 33 44',
                'email' => 'marie.kabore@email.com',
                'adresse' => 'Avenue Kwame Nkrumah, Bobo-Dioulasso',
                'ville' => 'Bobo-Dioulasso',
                'date_naissance' => '1990-08-20',
                'sexe' => 'F',
                'type' => 'particulier',
                'remarques' => 'Demande toujours des conseils sur les médicaments',
            ],
            [
                'numero_client' => 'CLI003',
                'nom' => 'TRAORE',
                'prenom' => 'Amadou',
                'telephone' => '+226 72 33 44 55',
                'email' => 'a.traore@entreprise.bf',
                'adresse' => 'Zone industrielle, Koudougou',
                'ville' => 'Koudougou',
                'date_naissance' => '1978-03-10',
                'sexe' => 'M',
                'type' => 'professionnel',
                'remarques' => 'Achats en gros pour son entreprise - Facturation requise',
            ],
            [
                'numero_client' => 'CLI004',
                'nom' => 'COMPAORE',
                'prenom' => 'Fatou',
                'telephone' => '+226 73 44 55 66',
                'email' => null, // Email optionnel
                'adresse' => 'Quartier Dapoya, Ouagadougou',
                'ville' => 'Ouagadougou',
                'date_naissance' => '1995-12-05',
                'sexe' => 'F',
                'type' => 'particulier',
                'remarques' => null,
            ],
            [
                'numero_client' => 'CLI005',
                'nom' => 'SAWADOGO',
                'prenom' => 'Ibrahim',
                'telephone' => '+226 74 55 66 77',
                'email' => 'ibrahim.sawadogo@email.com',
                'adresse' => 'Secteur 30, Ouagadougou',
                'ville' => 'Ouagadougou',
                'date_naissance' => '1982-07-25',
                'sexe' => 'M',
                'type' => 'particulier',
                'remarques' => 'Achète souvent des vitamines et compléments alimentaires',
            ],
            [
                'numero_client' => 'CLI006',
                'nom' => 'ZOUNGRANA',
                'prenom' => 'Sophie',
                'telephone' => '+226 75 66 77 88',
                'email' => 'sophie.z@gmail.com',
                'adresse' => 'Rue 12.50, Ouagadougou',
                'ville' => 'Ouagadougou',
                'date_naissance' => '1988-11-30',
                'sexe' => 'F',
                'type' => 'particulier',
                'remarques' => 'Cliente VIP - Très fidèle depuis 5 ans',
            ],
            [
                'numero_client' => 'CLI007',
                'nom' => 'CENTRE MEDICAL ESPOIR',
                'prenom' => 'Administration',
                'telephone' => '+226 76 77 88 99',
                'email' => 'contact@centreespoir.bf',
                'adresse' => 'Avenue Charles de Gaulle, Ouagadougou',
                'ville' => 'Ouagadougou',
                'date_naissance' => null, // Pas de date de naissance pour une entreprise
                'sexe' => null,
                'type' => 'professionnel',
                'remarques' => 'Centre médical - Commandes mensuelles importantes - Paiement à 30 jours',
            ],
            [
                'numero_client' => 'CLI008',
                'nom' => 'YAO',
                'prenom' => 'Koffi',
                'telephone' => '+226 77 88 99 00',
                'email' => null,
                'adresse' => 'Quartier Tampouy, Ouagadougou',
                'ville' => 'Ouagadougou',
                'date_naissance' => '1992-04-18',
                'sexe' => 'M',
                'type' => 'particulier',
                'remarques' => null,
            ],
            [
                'numero_client' => 'CLI009',
                'nom' => 'NIKIEMA',
                'prenom' => 'Alice',
                'telephone' => '+226 78 99 00 11',
                'email' => 'alice.nikiema@yahoo.fr',
                'adresse' => 'Secteur 22, Ouagadougou',
                'ville' => 'Ouagadougou',
                'date_naissance' => '1987-09-12',
                'sexe' => 'F',
                'type' => 'particulier',
                'remarques' => 'Diabétique - Achète régulièrement de l\'insuline',
            ],
            [
                'numero_client' => 'CLI010',
                'nom' => 'PHARMACIE DU SECTEUR 12',
                'prenom' => 'Grossiste',
                'telephone' => '+226 79 00 11 22',
                'email' => 'grossiste@secteur12.bf',
                'adresse' => 'Secteur 12, Ouagadougou',
                'ville' => 'Ouagadougou',
                'date_naissance' => null,
                'sexe' => null,
                'type' => 'professionnel',
                'remarques' => 'Pharmacie partenaire - Remise de 10% sur tous les achats',
            ],
        ];

        // Créer tous les clients
        foreach ($clients as $clientData) {
            Client::create($clientData);
        }

        // Message de confirmation dans le terminal
        $this->command->info('✅ 10 clients de test créés avec succès !');
        $this->command->info('   - 7 Particuliers');
        $this->command->info('   - 3 Professionnels');
    }
}