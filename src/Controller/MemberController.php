<?php

namespace App\Controller;

use App\Entity\Band;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/bands/{band}/members", name="members_", requirements={"band"="\d+"})
 */
class MemberController extends AbstractController
{
    /**
     * @Route("/add", name="add")
     */
    public function add(Band $band)
    {
        $this->getUser()->requireMemberOf($band);

        return $this->render('member/index.html.twig', [
            'controller_name' => 'MemberController',
        ]);
    }

    /**
     * @Route("/remove/{member}", name="remove")
     */
    public function remove(Band $band, User $member)
    {
        $this->getUser()->requireMemberOf($band);
        $member->requireMemberOf($band);

        $objectManager = $this->getDoctrine()->getManager();

        if ($band->getMembers()->count() == 1) {
            $objectManager->remove($band);
        } else {
            $band->removeMember($member);
            $objectManager->persist($band);
        }

        $objectManager->flush();

        if ($this->getUser() == $member)
            return $this->redirectToRoute("bands_all");

        return $this->redirectToRoute("bands_one", [
            "band" => $band->getId()
        ]);
    }
}
