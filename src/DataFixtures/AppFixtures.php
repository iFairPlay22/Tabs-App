<?php

namespace App\DataFixtures;

use Faker;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

abstract class AppFixtures extends Fixture
{
    protected static $faker;

    private static function init()
    {
        if (self::$faker == null)
            self::$faker = Faker\Factory::create();
    }

    protected function createMany(ObjectManager $manager, string $className, int $count)
    {
        self::init();

        for ($i = 0; $i < $count; $i++) {

            $entity = new $className();
            $this->loadData($entity);

            $manager->persist($entity);
            $this->addReference($className . '_' . $i, $entity);
        }

        $manager->flush();
    }

    protected abstract function loadData(&$entity);

    public function addCustumReference(string $className, int $i, object $entity): void
    {
        $this->addReference(self::hashReference($className, $i), $entity);
    }

    public function getCustumReference(string $className, int $i): object
    {
        return $this->getReference(self::hashReference($className, $i));
    }

    private static function hashReference(string $className, int $i): string
    {
        return $className . '_' . $i;
    }
}
