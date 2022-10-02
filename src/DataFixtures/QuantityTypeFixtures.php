<?php

namespace App\DataFixtures;

use App\Entity\QuantityType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuantityTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $quantityType = (new QuantityType())->setName('botte')->setPluralName('bottes');
        $manager->persist($quantityType);
        $quantityType = (new QuantityType())->setName('cuillère à café')->setPluralName('cuillères à café');
        $manager->persist($quantityType);
        $quantityType = (new QuantityType())->setName('cuillère à soupe')->setPluralName('cuillères à soupe');
        $manager->persist($quantityType);
        $quantityType = (new QuantityType())->setName('g');
        $manager->persist($quantityType);
        $quantityType = (new QuantityType())->setName('Kg');
        $manager->persist($quantityType);
        $quantityType = (new QuantityType())->setName('L');
        $manager->persist($quantityType);
        $quantityType = (new QuantityType())->setName('mL');
        $manager->persist($quantityType);
        $quantityType = (new QuantityType())->setName('tranche')->setPluralName('tranches');
        $manager->persist($quantityType);

        $manager->flush();
    }
}
