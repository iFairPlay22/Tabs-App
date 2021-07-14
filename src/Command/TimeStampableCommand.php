<?php

namespace App\Command;

use App\Repository\BandRepository;
use App\Repository\SongRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TimeStampableCommand extends Command
{
    protected static $defaultName = 'app:TimeStampableCommand';
    protected static $defaultDescription = 'Compléter les champs created at / updated at';

    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * @var BandRepository $bandRepo
     */
    private $bandRepo;

    /**
     * @var SongRepository $songRepo
     */
    private $songRepo;

    /**
     * @var UserRepository $userRepo
     */
    private $userRepo;

    public function __construct(EntityManagerInterface $em, BandRepository $bandRepo, SongRepository $songRepo, UserRepository $userRepo)
    {
        $this->em = $em;
        $this->bandRepo = $bandRepo;
        $this->songRepo = $songRepo;
        $this->userRepo = $userRepo;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $dataArray = array_merge(
            $this->bandRepo->findAll(),
            $this->songRepo->findAll(),
            $this->userRepo->findAll()
        );

        $date = new DateTime();
        $updated = 0;
        $total = 0;

        foreach ($dataArray as $data) {

            $updateCA = $data->getCreatedAt() == NULL;
            $updateUA = $data->getUpdatedAt() == NULL;

            if ($updateCA) $data->setCreatedAt($date);
            if ($updateUA) $data->setUpdatedAt($date);

            if ($updateCA || $updateUA) {
                $this->em->persist($data);
                $updated++;
            }

            $total++;
        }

        if ($updated != 0)
            $this->em->flush();

        $io->success("Updated $updated fields over $total!");

        return Command::SUCCESS;
    }
}
