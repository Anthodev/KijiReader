<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Repository\UserStoryRepository;
use App\Utils\FeedHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class KijireaderCheckNewsfeedCommand extends Command
{
    protected static $defaultName = 'kijireader:check-newsfeed';

    private $userRepository;
    private $feedHandler;
    private $em;

    public function __construct(UserRepository $userRepository, FeedHandler $feedHandler, EntityManagerInterface $em)
    {
        $this->userRepositories = $userRepository;
        $this->feedHandler = $feedHandler;
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Check the newsfeed of every user for new entries')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $users = $this->userRepository->findAll();
        
        foreach ($users as $user) {
            $feeds = $user->getFeeds();

            if ($feeds->count() > 0) {
                foreach ($feeds as $feed) {
                    if ($feed->getStories->last()->getDate() < strtotime("-5 minutes")) {
                        $this->feedHandler->processFeed($feed, $user);
                    }
                }

                $this->em->flush();
                $this->em->clear();
            }
        }

        $io->success('All the feeds have been updated.');
    }
}
