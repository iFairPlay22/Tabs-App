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
     * @Route("/songs/{tag}/{search}", name="songs")
     */
    public function getSongsByTag($tag, $search = NULL, TagRepository $tagRepository, SongRepository $songRepository)
    {
        $tag = $tagRepository->findOneBy([ "label" => "#".$tag ]);

        if (!$tag) {
            return $this->json([]);
        }

        $songs = [];
        if ($search) {
            $songs = $songRepository->findByNameAndTag($search, $tag);
        } else {
            $songs = $songRepository->findBy( [ "tag" => $tag ] );
        }

        if (!$songs) {
            return $this->json([]);
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
