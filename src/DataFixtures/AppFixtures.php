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
        // Create User
        $user = new User();
        $user->setEmail('test@test.com');
        $user->setRoles(['ROLE_USER']);
        $password = $this->hasher->hashPassword($user, 'password');
        $user->setPassword($password);
        $manager->persist($user);

        // Create Settings
        $setting = new Setting();
        $setting->setUser($user);
        $setting->setSettings(['theme' => 'light']);
        $manager->persist($setting);

        // Create Aquarium 1
        $aquarium1 = new Aquarium();
        $aquarium1->setName('Living Room Tank');
        $aquarium1->setCapacity(120.5);
        $aquarium1->setType('Freshwater');
        $aquarium1->setUser($user);
        $manager->persist($aquarium1);

        // Fishes for Aquarium 1
        $fishNames1 = ['Goldie', 'Finny', 'Bubbles'];
        foreach ($fishNames1 as $name) {
            $fish = new Fish();
            $fish->setName($name);
            $fish->setAquarium($aquarium1);
            $manager->persist($fish);
        }

        // Data for Aquarium 1 (Generate some history)
        for ($i = 0; $i < 5; $i++) {
            $data = new Data();
            $data->setAquarium($aquarium1);
            $data->setCreatedAt(new \DateTimeImmutable("-{$i} days"));
            $data->setTemp(25.0 + (rand(-10, 10) / 10)); // 24.0 - 26.0
            $data->setPh(7.0 + (rand(-5, 5) / 10));
            $data->setKh(4.0 + (rand(-1, 1)));
            $data->setGh(8.0 + (rand(-2, 2)));
            $data->setCl2(0.0);
            $data->setNo2(0.0);
            $data->setNo3(10.0 + (rand(-5, 5)));
            $data->setObservation('Routine check.');
            $manager->persist($data);
        }

        // Create Aquarium 2
        $aquarium2 = new Aquarium();
        $aquarium2->setName('Office Betta Tank');
        $aquarium2->setCapacity(20.0);
        $aquarium2->setType('Freshwater');
        $aquarium2->setUser($user);
        $manager->persist($aquarium2);

        // Fish for Aquarium 2
        $fish = new Fish();
        $fish->setName('Alpha');
        $fish->setAquarium($aquarium2);
        $manager->persist($fish);

        // Data for Aquarium 2
        $data2 = new Data();
        $data2->setAquarium($aquarium2);
        $data2->setCreatedAt(new \DateTimeImmutable());
        $data2->setTemp(26.5);
        $manager->persist($data2);

        $manager->flush();
    }
}
