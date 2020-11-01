<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\FormUtils;
use App\Form\UserType;
use App\Security\LoginFormAuthenticator;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function home(): Response
    {
        if ($this->getUser())
            return $this->redirectToRoute('bands_all');

        return $this->render('security/home.html.twig', []);
    }

    /**
     * @Route("/sign-up", name="app_sign_up")
     */
    public function signUp(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $formAuthenticator): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user, [
            "submit_label" => "Create your account",
            "repeat_password" => true
        ]);

        if (FormUtils::updateDBIfValid($request, $form, $this->getDoctrine()->getManager())) {

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $user->getPassword()
                )
            );

            $objectManager = $this->getDoctrine()->getManager();
            $objectManager->persist($user);
            $objectManager->flush();

            $request->query->set('user', $user);

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $formAuthenticator,
                'main'
            );
        }

        return $this->render('security/signUp.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/my-account", name="app_account_edit")
     * @IsGranted("ROLE_USER")
     **/

    public function myAccount(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user, [
            "submit_label" => "Modify",
            "repeat_password" => true
        ]);

        $objectManager = $this->getDoctrine()->getManager();

        if (FormUtils::updateDBIfValid($request, $form, $objectManager)) {

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $user->getPassword()
                )
            );

            $objectManager->persist($user);
            $objectManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('security/myAccount.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser())
            return $this->redirectToRoute('bands_all');

        $user = new User();
        $user->setEmail($authenticationUtils->getLastUsername());

        $form = $this->createForm(UserType::class, $user, [
            "submit_label" => "Sign in",
            "repeat_password" => false
        ]);

        return $this->render('security/login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }
}
