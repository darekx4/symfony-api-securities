<?php

namespace App\DataFixture;

use App\Constant\Attributes;
use App\Entity\Attribute;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use ReflectionClass;

class AttributeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $currentAttributes = new ReflectionClass(Attributes::class);

        foreach ($currentAttributes->getConstants() as $key => $currentAttribute) {
            $attribute = new Attribute();
            $attribute->setName($currentAttribute);
            $manager->persist($attribute);
        }
        $manager->flush();
    }
}
