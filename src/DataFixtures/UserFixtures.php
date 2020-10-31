<?php

namespace App\DataFixtures;

use App\Entity\Band;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends AppFixtures implements DependentFixtureInterface
{
    public static $total = 2;
    public static $maxBandsByUser = 7;

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->createMany($manager, User::class, self::$total);
    }

    protected function loadData(&$entity)
    {
        $entity->setEmail(self::$faker->freeEmail);
        $entity->setPassword($this->passwordEncoder->encodePassword(
            $entity,
            self::$faker->password
        ));

        for ($i = 0; $i < self::$faker->numberBetween(1, BandFixtures::$total - 1); $i++) {

            $band = $this->getCustumReference(Band::class, $i);
            $entity->addBand($band);
        }
    }

    public function getDependencies()
    {
        return [
            BandFixtures::class
        ];
    }
}
