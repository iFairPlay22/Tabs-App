<?php

namespace App\DataFixtures;

use App\Entity\Band;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BandFixtures extends AppFixtures
{
    public static $total = 5;

    public function load(ObjectManager $manager)
    {
        $this->createMany($manager, Band::class, self::$total);
    }

    protected function loadData(&$entity)
    {
        $entity->setName(self::$faker->realText(30));
    }
}
