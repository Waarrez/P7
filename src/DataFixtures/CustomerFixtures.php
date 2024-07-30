<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomerFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $customer = new Customer();

        $customer->setEmail("utilisateur@gmail.com")
            ->setName($faker->name)
            ->setPassword($this->hasher->hashPassword($customer, "utilisateur"))
            ->setPhoneNumber($faker->phoneNumber)
            ->setAddress($faker->address)
            ->setCity($faker->city);

        $manager->persist($customer);

        for ($i = 0; $i < 15; $i++) {
            $customer = new Customer();

            $customer->setEmail($faker->email)
                 ->setName($faker->name)
                 ->setPassword($this->hasher->hashPassword($customer, $faker->password))
                 ->setPhoneNumber($faker->phoneNumber)
                 ->setAddress($faker->address)
                 ->setCity($faker->city);

            $manager->persist($customer);
        }

        $manager->flush();
    }
}