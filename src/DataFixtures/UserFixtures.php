<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\CustomerRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly CustomerRepository $customerRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $customers = $this->customerRepository->findAll();

        foreach ($customers as $customer) {
            $user = new User();

            $user->setEmail($faker->email)
                 ->setFirstName($faker->firstName)
                 ->setLastName($faker->lastName)
                 ->setPhoneNumber($faker->phoneNumber)
                 ->setCustomer($customer);

            $manager->persist($user);
        }

        $manager->flush();
    }

    private function getItem(array $datas): mixed
    {
        return $datas[array_rand($datas)];
    }
}