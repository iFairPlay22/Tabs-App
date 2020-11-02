<?php

namespace App\Controller;

use App\Entity\Band;
use App\Entity\Song;
use App\Form\BandType;
use App\Form\FormUtils;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/bands", name="bands_")
 */
class BandController extends AbstractController
{
    /**
     * @Route("", name="all")
     */
    public function all(Request $request)
    {
        $user = $this->getUser();
        $search = $request->get("band_search");

        if ($search != NULL && $search != "") {
            $bands = $this->getDoctrine()
                ->getRepository(Band::class)
                ->findByName($user, $search);
        } else {
            $bands = $user->getBands();
        }

        return $this->render('band/all.html.twig', [
            'user' => $user,
            'bands' => $bands,
            'search' => $search
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
        $band = new Band();
        $band->addMember($this->getUser());

        $form = $this->createForm(BandType::class, $band, ["submit_label" => "Create"]);

        if (FormUtils::updateDBIfValid($request, $form, $this->getDoctrine()->getManager()))
            return $this->redirectToRoute('bands_all');

        return $this->render('band/create.html.twig', [
            'band' => $band,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{band}/edit", name="edit", requirements={"band"="\d+"})
     */
    public function edit(Request $request, Band $band)
    {
        $this->getUser()->requireMemberOf($band);

        $form = $this->createForm(BandType::class, $band, ["submit_label" => "Modify"]);

        if (FormUtils::updateDBIfValid($request, $form, $this->getDoctrine()->getManager()))
            return $this->redirectToRoute('bands_all');

        return $this->render('band/edit.html.twig', [
            'band' => $band,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{band}/delete", name="delete", requirements={"band"="\d+"})
     */
    public function delete(Band $band)
    {
        $this->getUser()->requireMemberOf($band);

        $objectManager = $this->getDoctrine()
            ->getManager();
        $objectManager->remove($band);
        $objectManager->flush();

        return $this->redirectToRoute('bands_all');
    }


    /**
     * @Route("/{band}", name="one")
     */
    public function one(Request $request, Band $band)
    {
        $this->getUser()->requireMemberOf($band);

        $search = $request->get("song_search");

        if ($search != NULL && $search != "") {
            $songs = $this->getDoctrine()
                ->getRepository(Song::class)
                ->findWhereLike($band, $search);
        } else {
            $songs = $band->getSongs();
        }

        return $this->render('song/all.html.twig', [
            'band' => $band,
            'songs' => $songs,
            'search' => $search
        ]);
    }
}
