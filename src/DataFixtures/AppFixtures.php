<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i = 0; $i <= mt_rand(12, 25); $i++) {
            $product = new Product;
            $product->setTitle($this->faker->sentence(2))
                ->setImage($this->faker->imageUrl(640, 480, 'produit',))
                ->setPrice($this->faker->randomFloat(2, 15, 900));

            $manager->persist($product);
        }

        $manager->flush();
    }
}
