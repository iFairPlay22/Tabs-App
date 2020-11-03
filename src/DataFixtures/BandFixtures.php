<?php

namespace App\DataFixtures;

use App\Entity\Band;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BandFixtures extends AppFixtures implements DependentFixtureInterface
{
    public static $total = 500;

    public function load(ObjectManager $manager)
    {
        $this->createMany($manager, Band::class, self::$total);
    }

    protected function loadData(&$entity)
    {
        $entity->setName(self::$faker->realText(30));

        $user = $this->getCustumReference(
            User::class,
            self::$faker->numberBetween(0, UserFixtures::$total - 1)
        );

        $user->addBand($entity);
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
