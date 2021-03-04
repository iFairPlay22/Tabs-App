<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class TagFixtures extends AppFixtures implements FixtureGroupInterface
{
    public static $total = 10;

    public function load(ObjectManager $manager)
    {
        $this->createMany($manager, Tag::class, self::$total);
    }

    protected function loadData(&$entity)
    {
        $entity->setLabel("#" . self::$faker->word());
        $entity->setColor(self::$faker->safeColorName());
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }
}
