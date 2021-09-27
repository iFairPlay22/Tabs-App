<?php

namespace App\Controller;

use App\Repository\SongRepository;
use App\Repository\TagRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/songs/{tag}", name="all")
     */
    public function getSongsByTag($tag, TagRepository $tagRepository, SongRepository $songRepository)
    {
        $tag = $tagRepository->findOneBy([ "label" => "#".$tag ]);

        if (!$tag) {
            return $this->json("No tags...");
        }

        $songs = $songRepository->findBy([ "tag" => $tag ]);

        if (!$songs) {
            return $this->json("No songs...");
        }

        return $this->json(
            array_map(
                function ($song) {
                    return [
                        "songName"  => $song->getSongName(),
                        "groupName" => $song->getGroupName()
                    ];
                },
                $songs
            )
        );
    }
}
