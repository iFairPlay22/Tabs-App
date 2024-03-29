<?php

namespace App\Controller;

use App\Entity\Band;
use App\Entity\Song;
use App\Form\FormUtils;
use App\Form\SongType;
use App\Form\TagType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/bands/{band}/songs", name="songs_", requirements={"band"="\d+"})
 */
class SongController extends AbstractController
{

    /**
     * @Route("/{song}", name="one", requirements={"song"="\d+"})
     */
    public function one(Band $band, Song $song)
    {
        $this->getUser()->requireMemberOf($band);
        $song->requireSongOf($band);

        return $this->render('song/one.html.twig', [
            'band' => $band,
            'song' => $song,
            'tabs' => [
                [
                    'id' => 'guitar',
                    'title' => 'Guitar',
                    'content' => $song->getGuitarTabs()
                ],
                [
                    'id' => 'lyrics',
                    'title' => 'Lyrics',
                    'content' => $song->getLyrics()
                ]
            ]
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request, Band $band)
    {
        $this->getUser()->requireMemberOf($band);

        $song = new Song();
        $song->setBand($band);

        $form = $this->createForm(SongType::class, $song, ["submit_label" => "Create"]);

        if (FormUtils::updateDBIfValid($request, $form, $this->getDoctrine()->getManager()))
            return $this->redirectToRoute('songs_one', [
                'band' => $band->getId(),
                'song' => $song->getId()
            ]);

        return $this->render('song/create.html.twig', [
            'band' => $band,
            'song' => $song,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{song}/edit", name="edit", requirements={"song"="\d+"})
     */
    public function edit(Request $request, Band $band, Song $song)
    {
        $this->getUser()->requireMemberOf($band);
        $song->requireSongOf($band);

        $form = $this->createForm(SongType::class, $song, ["submit_label" => "Modify"]);

        if (FormUtils::updateDBIfValid($request, $form, $this->getDoctrine()->getManager()))
            return $this->redirectToRoute('songs_one', [
                'band' => $band->getId(),
                'song' => $song->getId(),
            ]);

        return $this->render('song/edit.html.twig', [
            'band' => $band,
            'song' => $song,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{song}/delete", name="delete", requirements={"song"="\d+"})
     */
    public function delete(Band $band, Song $song)
    {
        $this->getUser()->requireMemberOf($band);
        $song->requireSongOf($band);

        $band = $song->getBand();

        $objectManager = $this->getDoctrine()
            ->getManager();
        $objectManager->remove($song);
        $objectManager->flush();

        return $this->redirectToRoute('bands_one', [
            'band' => $band->getId()
        ]);
    }
}
