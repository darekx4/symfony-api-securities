<?php

namespace App\DataFixtures;

use App\Constants\Securities;
use App\Entity\Security;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use ReflectionClass;

class SecurityFixtures extends Fixture
{
    public function load(ObjectManager $manager) :void
    {
        $currentSecurities = new ReflectionClass(Securities::class);

        foreach ($currentSecurities->getConstants() as $key => $currentSecurity) {
            $security = new Security();
            $security->setSymbol($currentSecurity);
            $manager->persist($security);
        }
        $manager->flush();
    }
}
