<?php

namespace App\Controller;

use App\Entity\Band;
use App\Entity\Song;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MigrationController extends AbstractController
{
    private $tables = ['song', 'band_user', 'band', 'user'];

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
                        ->getDB($this->tables);
                }
            )
        );
    }

    /**
     * @Route("/migration/set", name="migration_set")
     */
    public function setDB(Request $request, HttpClientInterface $client)
    {
        return $this->json(
            $this->secureAction(
                $request,
                function () use ($client) {

                    # FETCH PROD URL #
                    $data = $client->request(
                        'GET',
                        'http://my-bands-app.herokuapp.com/index.php/migration/get'
                            . '?_user_=_user_&_password_=_password_'
                    )->toArray();

                    # RESET DB #
                    $this->getDoctrine()
                        ->getRepository(User::class)
                        ->resetDB($this->tables);

                    $entityManager = $this->getDoctrine()->getManager();

                    # CREATE USERS #

                    $users = [];

                    foreach ($data['user'] as $userList) {

                        $user = new User();

                        $user->setId($userList["id"]);
                        $user->setEmail($userList["email"]);
                        $user->setPassword($userList["password"]);
                        $user->setRoles(explode(',', $userList["roles"]));

                        $users[$user->getId()] = $user;
                    }

                    # CREATE BANDS #

                    $bands = [];

                    foreach ($data['band'] as $bandList) {

                        $band = new Band();

                        $band->setId($bandList["id"]);
                        $band->setName($bandList["name"]);

                        $bands[$band->getId()] = $band;
                    }

                    # CREATE USER_BANDS #

                    foreach ($data['band_user'] as $bandUserList) {

                        $bands[$bandUserList["band_id"]]->addMember(
                            $users[$bandUserList["user_id"]]
                        );
                    }

                    # CREATE SONGS #

                    $songs = [];

                    foreach ($data['song'] as $songList) {

                        $song = new Song();

                        $song->setId($songList["id"]);
                        $song->setCapo($songList["capo"]);
                        $song->setSongName($songList["song_name"]);
                        $song->setGroupName($songList["group_name"]);
                        $song->setContent($songList["content"]);

                        $song->setBand(
                            $bands[$songList["band_id"]]
                        );

                        $songs[$song->getId()] = $song;
                    }

                    ## UPDATE DB ##

                    foreach ([$users, $bands, $songs] as $group) {
                        foreach ($group as $id => $obj) {
                            $entityManager->persist($obj);
                        }
                    }

                    $entityManager->flush();

                    return "Done";
                }
            )
        );
    }
}
