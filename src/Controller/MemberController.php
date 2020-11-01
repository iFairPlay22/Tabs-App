<?php

namespace App\Controller;

use App\Entity\Band;
use App\Entity\User;
use App\Util\ViewCode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/bands/{band}/members", name="members_", requirements={"band"="\d+"})
 */
class MemberController extends AbstractController
{
    /**
     * @Route("/manage", name="manage")
     */
    public function manage(Band $band)
    {
        $user = $this->getUser();
        $user->requireMemberOf($band);

        return $this->render('member/manage.html.twig', [
            'band' => $band,
            'user' => $user
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request, Band $band)
    {
        $user = $this->getUser();
        $user->requireMemberOf($band);

        $userCode = $request->get("user_code");

        if ($userCode != NULL) {

            $member = $this->getDoctrine()
                ->getRepository(User::class)
                ->find(
                    ViewCode::idFromCode($userCode)
                );

            if ($member == NULL) {
                $this->addFlash('user_code_error', sprintf(
                    "Code %s don't match with any user!",
                    $userCode
                ));
            } else if ($user->getId() == $member->getId()) {
                $this->addFlash(
                    'user_code_error',
                    "You are already in this band!"
                );
            } else if ($member->getBands()->contains($band)) {
                $this->addFlash('user_code_error', sprintf(
                    "User %s is already in this band!",
                    $userCode
                ));
            } else {
                $band->addMember($member);

                $objectManager = $this->getDoctrine()->getManager();
                $objectManager->persist($band);
                $objectManager->flush();

                $this->addFlash('user_code_ok', sprintf(
                    'User %s is now a member of your band!',
                    $userCode
                ));
            }
        }

        return $this->render('member/add.html.twig', [
            'band' => $band,
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
        $members = $band->getMembers()->count();

        $band->removeMember($member);
        $objectManager->persist($band);

        if ($members == 1)
            $objectManager->remove($band);

        $objectManager->flush();

        if ($this->getUser() == $member)
            return $this->redirectToRoute("bands_all");

        return $this->redirectToRoute("members_manage", [
            "band" => $band->getId()
        ]);
    }
}
