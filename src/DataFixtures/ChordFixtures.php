<?php

namespace App\DataFixtures;

use App\Entity\Chord;
use App\Entity\Riff;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ChordFixtures extends AppFixtures implements DependentFixtureInterface
{
    public static $total = 25;

    public function load(ObjectManager $manager)
    {
        $this->createMany($manager, Chord::class, self::$total);
    }

    protected function loadData(&$entity)
    {
        $entity->setChord(self::$faker->randomElement(["C", "D", "E"]));

        $riff = $this->getCustumReference(
            Riff::class,
            self::$faker->numberBetween(0, RiffFixtures::$total - 1)
        );

        $riff->addChord($entity);

        $entity->setOrderBy(count($riff->getChords()));
    }

    public function getDependencies()
    {
        return [
            RiffFixtures::class
        ];
    }
}
