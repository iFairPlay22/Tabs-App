<?php

namespace App\DataFixtures;

use App\Entity\Band;
use App\Entity\Song;
use App\Entity\Tag;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class SongFixtures extends AppFixtures implements DependentFixtureInterface, FixtureGroupInterface
{
    public static $total = 1000;

    public function load(ObjectManager $manager)
    {
        $this->createMany($manager, Song::class, self::$total);
    }

    protected function loadData(&$entity)
    {
        $entity->setCapo(self::$faker->randomElement(["C", "D", "E"]));
        $entity->setSongName(self::$faker->realText(30));
        $entity->setGroupName(self::$faker->realText(30));
        $entity->setContent(self::$faker->realText(1000));
        $entity->setLyrics(self::$faker->realText(1000));

        $band = $this->getCustumReference(
            Band::class,
            self::$faker->numberBetween(0, BandFixtures::$total - 1)
        );

        $band->addSong($entity);

        $tag = $this->getCustumReference(
            Tag::class,
            self::$faker->numberBetween(0, TagFixtures::$total - 1)
        );

        $tag->addSong($entity);
    }

    public function getDependencies()
    {
        return [
            TagFixtures::class,
            BandFixtures::class
        ];
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }
}
