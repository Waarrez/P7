<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Phone;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ){}

    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i <= 10; $i++) {

            $user = new User();
            $user->setName("Nom n° $i");

            $client = new Client();
            $client->setUsername("Utilisateur n° $i")
                   ->setUser($user);

            $hashPass = $this->hasher->hashPassword($client, "password");
            $client->setPassword($hashPass);

            $phone = new Phone();
            $phone->setName("Téléphone n° $i")
                  ->setContent("Description")
                  ->setStorage(random_int(8, 128))
                  ->setPrice(rand(1, 1000) / 10)
                  ->setColor("Red")
                  ->setImage("image.png");

            $manager->persist($phone);
            $manager->persist($user);
            $manager->persist($client);
        }

        $manager->flush();
    }
}
