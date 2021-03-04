<?php

namespace App\DataFixtures;

use App\Entity\Band;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends AppFixtures implements FixtureGroupInterface
{
    public static $total = 2;

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
            "12345"
            // self::$faker->password
        ));
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }
}
