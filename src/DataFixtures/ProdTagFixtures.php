<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ProdTagFixtures extends AppFixtures implements FixtureGroupInterface
{

    public function load(ObjectManager $manager)
    {
        $params = [
            [
                "label" => "Idée",
                "color" => "red",
            ],
            [
                "label" => "Projet",
                "color" => "orange",
            ],
            [
                "label" => "Maitrisée",
                "color" => "green",
            ]
        ];

        foreach ($params as $param) {
            $entity = new Tag();

            $entity->setLabel("#" . $param["label"]);
            $entity->setColor($param["color"]);

            $manager->persist($entity);
        }

        $manager->flush();
    }

    protected function loadData(&$entity)
    {
        
    }

    public static function getGroups(): array
    {
        return ['prod'];
    }
}
