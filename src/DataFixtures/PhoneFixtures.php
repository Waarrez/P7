<?php

namespace App\DataFixtures;

use App\Entity\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PhoneFixtures extends Fixture
{

    public const BRAND = ['Samsung', 'Apple', 'Motorola', 'Xiamoi'];
    public const STORAGES = [32, 64, 128, 256, 512, 1024];
    public const MODEL = ['Pro', 'Max'];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 15; $i++) {

            $phone = new Phone();
            $phone->setBrand($this->getItem(self::BRAND))
                  ->setStorage($this->getItem(self::STORAGES))
                  ->setModel($this->getItem(self::MODEL))
                  ->setPictures("image.png")
                  ->setPrice($faker->randomFloat(2, 200, 1400));

            $manager->persist($phone);
        }

        $manager->flush();
    }

    private function getItem(array $datas): mixed
    {
        return $datas[array_rand($datas)];
    }
}