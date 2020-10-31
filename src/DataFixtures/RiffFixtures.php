<?php

namespace App\DataFixtures;

use App\Entity\Riff;
use App\Entity\Song;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RiffFixtures extends AppFixtures implements DependentFixtureInterface
{
    public static $total = 25;

    public function load(ObjectManager $manager)
    {
        $this->createMany($manager, Riff::class, self::$total);
    }

    protected function loadData(&$entity)
    {
        $entity->setName(self::$faker->realText(30));

        $song = $this->getCustumReference(
            Song::class,
            self::$faker->numberBetween(0, SongFixtures::$total - 1)
        );

        $song->addRiff($entity);
    }

    public function getDependencies()
    {
        return [
            SongFixtures::class
        ];
    }
}
