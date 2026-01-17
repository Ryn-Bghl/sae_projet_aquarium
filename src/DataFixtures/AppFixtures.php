<?php

namespace App\DataFixtures;

use App\Entity\Aquarium;
use App\Entity\Data;
use App\Entity\Fish;
use App\Entity\Setting;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        // --- USERS ---
        
        // Guest User
        $guest = new User();
        $guest->setEmail('guest@aquatrack.fr');
        $guest->setRoles(['ROLE_USER']);
        $guest->setPassword($this->hasher->hashPassword($guest, 'guest_password_not_used'));
        $manager->persist($guest);

        // Admin User
        $admin = new User();
        $admin->setEmail('admin@aquatrack.fr');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // --- AQUARIUMS ---

        $tanks = [
            [
                'name' => 'Récifal de Salon',
                'capacity' => 450.0,
                'type' => 'Eau de mer',
                'user' => $guest,
                'fish' => ['Chirurgien Jaune', 'Poisson Clown (x2)', 'Gobie à points oranges', 'Demoiselle Bleue'],
                'params' => ['temp' => [24.5, 26.5], 'ph' => [8.0, 8.4], 'no3' => [2, 10], 'no2' => [0, 0.05]]
            ],
            [
                'name' => 'Amazonien Discus',
                'capacity' => 300.0,
                'type' => 'Eau douce tropicale',
                'user' => $guest,
                'fish' => ['Discus Bleu (x4)', 'Cardinalis (x20)', 'Corydoras Sterbai (x10)', 'Ancistrus'],
                'params' => ['temp' => [27.5, 29.0], 'ph' => [6.2, 6.8], 'no3' => [10, 25], 'no2' => [0, 0.1]]
            ],
            [
                'name' => 'Nano Crevettes',
                'capacity' => 30.0,
                'type' => 'Eau douce',
                'user' => $guest,
                'fish' => ['Crevettes Red Cherry (x15)', 'Escargot Neritina'],
                'params' => ['temp' => [20.0, 23.0], 'ph' => [7.0, 7.5], 'no3' => [5, 15], 'no2' => [0, 0.02]]
            ],
            [
                'name' => 'Bac de Bureau (Bettas)',
                'capacity' => 20.0,
                'type' => 'Eau douce',
                'user' => $admin,
                'fish' => ['Betta Splendens', 'Escargot Planorbe'],
                'params' => ['temp' => [25.0, 26.5], 'ph' => [6.8, 7.2], 'no3' => [5, 20], 'no2' => [0, 0.05]]
            ]
        ];

        foreach ($tanks as $tankData) {
            $aquarium = new Aquarium();
            $aquarium->setName($tankData['name']);
            $aquarium->setCapacity($tankData['capacity']);
            $aquarium->setType($tankData['type']);
            $aquarium->setUser($tankData['user']);
            $manager->persist($aquarium);

            // Create Fish
            foreach ($tankData['fish'] as $fishName) {
                $fish = new Fish();
                $fish->setName($fishName);
                $fish->setAquarium($aquarium);
                $manager->persist($fish);
            }

            // Create Data History (last 7 days)
            $params = $tankData['params'];
            for ($i = 7; $i >= 0; $i--) { 
                // Generate a measurement every day
                $data = new Data();
                $data->setAquarium($aquarium);
                
                // Date logic
                $date = new \DateTimeImmutable("-{$i} days");
                // Randomize time a bit
                $hour = rand(8, 20);
                $min = rand(0, 59);
                $data->setCreatedAt($date->setTime($hour, $min));

                // Param logic with small fluctuations
                $temp = $params['temp'][0] + (rand(0, 100) / 100) * ($params['temp'][1] - $params['temp'][0]);
                $ph = $params['ph'][0] + (rand(0, 100) / 100) * ($params['ph'][1] - $params['ph'][0]);
                $no3 = $params['no3'][0] + (rand(0, 100) / 100) * ($params['no3'][1] - $params['no3'][0]);
                $no2 = $params['no2'][0] + (rand(0, 100) / 100) * ($params['no2'][1] - $params['no2'][0]);

                $data->setTemp(round($temp, 1));
                $data->setPh(round($ph, 2));
                $data->setNo3(round($no3, 1));
                $data->setNo2(round($no2, 3));
                
                // Fixed/Random other params
                $data->setKh(rand(3, 12));
                $data->setGh(rand(5, 15));
                $data->setCl2(0);
                
                if (rand(1, 10) > 8) {
                    $observations = ['Changement d\'eau de 10%', 'Nettoyage des vitres', 'Taille des plantes', 'Ajout d\'engrais', 'Poissons en pleine forme'];
                    $data->setObservation($observations[array_rand($observations)]);
                } else {
                    $data->setObservation('Contrôle de routine.');
                }

                $manager->persist($data);
            }
        }

        $manager->flush();
    }
}