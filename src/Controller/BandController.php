<?php

namespace App\Controller;

use App\Entity\Band;
use App\Form\BandType;
use App\Form\FormUtils;
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
    public function all()
    {
        return $this->render('band/all.html.twig', [
            'bands' => $this->getUser()
                ->getBands()
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
        $band = new Band();
        $band->addMember($this->getUser());

        $form = $this->createForm(BandType::class, $band, ["submit_label" => "Créer"]);

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

        $form = $this->createForm(BandType::class, $band, ["submit_label" => "Modifier"]);

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
    public function one(Band $band)
    {
        $this->getUser()->requireMemberOf($band);

        return $this->render('song/all.html.twig', [
            'band' => $band
        ]);
    }
}
