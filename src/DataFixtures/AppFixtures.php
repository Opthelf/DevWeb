<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Utilisateur;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Utilisateur();
        $product->setName("SimondeDataFixtures");
        $product->setRole(["ROLE_ADMIN"]);
       // $product->setIsVerified(true);

        $manager->persist($product);

        $manager->flush();
    }
}
