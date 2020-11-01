<?php

namespace App\DataFixtures;

use App\Entity\Band;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends AppFixtures
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
            self::$faker->password
        ));
    }
}
