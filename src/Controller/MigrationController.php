<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class MigrationController extends AbstractController
{
    private function secureAction(Request $request, $callback)
    {
        $user = $request->get("_user_");
        $pwd  = $request->get("_password_");

        if ($user == NULL or $pwd == NULL)
            return "Missing _user_ or _password_";

        if (
            sha1($user) != "5d009af9d0bcab955e27903b508f6587739a4f01"
            ||
            sha1($pwd)  != "e30aa97f7d0b919568a98c2e9926cce7d6a25caf"
        )
            return "Bad credentials";

        return $callback();
    }
    /**
     * @Route("/migration/get", name="migration_get")
     */
    public function getDB(Request $request)
    {
        return $this->json(
            $this->secureAction(
                $request,
                function () {
                    return $this->getDoctrine()
                        ->getRepository(User::class)
                        ->getDB(
                            ['user', 'band_user', 'band', 'song']
                        );
                }
            )
        );
    }

    /**
     * @Route("/migration/set", name="migration_set")
     */
    public function setDB(Request $request)
    {
        return $this->json(
            $this->secureAction(
                $request,
                function () use ($request) {

                    $json = $this->getDB($request);

                    $data = json_decode($json);

                    return $data;
                }
            )
        );
    }
}
