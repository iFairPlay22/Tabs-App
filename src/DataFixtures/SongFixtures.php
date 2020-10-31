<?php

namespace App\DataFixtures;

use App\Entity\Band;
use App\Entity\Song;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SongFixtures extends AppFixtures implements DependentFixtureInterface
{
    public static $total = 20;

    public function load(ObjectManager $manager)
    {
        $this->createMany($manager, Song::class, self::$total);
    }

    protected function loadData(&$entity)
    {
        $entity->setCapo(self::$faker->randomElement(["C", "D", "E"]));
        $entity->setSongName(self::$faker->realText(30));
        $entity->setGroupName(self::$faker->realText(30));

        $band = $this->getCustumReference(
            Band::class,
            self::$faker->numberBetween(0, BandFixtures::$total - 1)
        );

        $band->addSong($entity);
    }

    public function getDependencies()
    {
        return [
            BandFixtures::class
        ];
    }
}
