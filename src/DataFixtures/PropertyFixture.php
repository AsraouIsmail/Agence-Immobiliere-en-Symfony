<?php

namespace App\DataFixtures;
use Faker\Factory;
use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PropertyFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 100; $i++) {
            $property = new Property();
            $property->setTitle($faker->words(3, true))
                     ->setDescription($faker->text(200))
                     ->setSurface($faker->numberBetween(60, 400))
                     ->setRooms($faker->numberBetween(1,10))
                     ->setBedrooms($faker->numberBetween(1,9))
                     ->setFloor($faker->numberBetween(1,10))
                     ->setHeat($faker->numberBetween(0, count(Property::HEAT) - 1))
                     ->setAddress($faker->address())
                     ->setPostalCode($faker->postcode())
                     ->setPrice($faker->numberBetween(5000, 1000000))
                     ->setCity($faker->city())
                     ->setSold(false)
                     ->setParking($faker->numberBetween(0, count(Property::PARKING) - 1))
                     ->setStatus($faker->numberBetween(0, count(Property::STATUS) - 1))
                     ->setType($faker->numberBetween(0, count(Property::TYPE) - 1))
                     ->setCountry($faker->country());
                     $manager->persist($property);
            
        }
        $manager->flush();
    }
}
