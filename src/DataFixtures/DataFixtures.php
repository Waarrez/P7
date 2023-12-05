<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Phone;
use App\Entity\Picture;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DataFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        $newFileName = null;

        for($i = 0; $i < 10; $i++) {

            $user = new User();
            $user->setUsername($faker->userName)
                 ->setEmail($faker->email)
                 ->setCreatedAt(new \DateTimeImmutable());

            $password = "password";
            $hashPassword = $this->hasher->hashPassword($user, $password);
            $user->setPassword($hashPassword);

            $brand = new Brand();
            $brand->setName("Marque n°$i");

            $picture = new Picture();
            $picture->setName("iphone.png")
                    ->setLocation("/img/");

            $phone = new Phone();
            $phone->setName("Téléphone n°$i")
                  ->setContent($faker->realText)
                  ->setColor($faker->hexColor())
                  ->setPrice($faker->randomFloat(2, 500, 2000))
                  ->setStorage($faker->randomNumber(3))
                  ->setBrand($brand);

            $picture->setPhone($phone);

            $brand->setPhone($phone);

            $manager->persist($user);
            $manager->persist($brand);
            $manager->persist($phone);
            $manager->persist($picture);
        }

        $manager->flush();
    }
}
