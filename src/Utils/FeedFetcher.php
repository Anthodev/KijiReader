<?php

namespace App\Utils;

use App\Entity\User;
use App\Entity\Story;
use App\Entity\UserStory;
use App\Repository\FeedRepository;
use App\Repository\StoryRepository;
use App\Repository\UserStoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class FeedFetcher {

    private $feedRepository;
    private $storyRepository;
    private $userStoryRepository;
    private $em;

    public function __construct(FeedRepository $feedRepository, StoryRepository $storyRepository, UserStoryRepository $userStoryRepository, EntityManagerInterface $em)
    {
        $this->feedRepository = $feedRepository;
        $this->storyRepository = $storyRepository;
        $this->userStoryRepository = $userStoryRepository;
        $this->em = $em;
    }

    public function getNewsfeed(User $user, $offset = 0)
    {
        $newsfeed = '';

        $feedIo = \FeedIo\Factory::create()->getFeedIo();
        $feeds = $user->getFeeds();
        $userStories = $this->userStoryRepository->findByUser($user);

        if ($feeds->count() > 0) {
            foreach ($feeds as $feed) {
                $stories = $feed->getStories();
                if ($stories->count() > 0) $lastStory = $stories->first();

                $result = null;

                if ($stories->count() > 0) $result = $feedIo->readSince($feed->getRssLink(), $lastStory->getDate());
                else $result = $feedIo->read(($feed->getRssLink()));

                foreach ($result->getFeed() as $item) {
                    $this->addStoryToDb($user, $feed, $item);
                }
            }

            $this->em->flush();
            $this->em->clear();

            // $stories = $this->storyRepository->findAllStoriesForUser($user, $offset);
            $userStories = $user->getUserStories();

            if (!is_null($userStories)) {
                $normalizer = new ObjectNormalizer();
                $encoder = new JsonEncoder();

                $serializer = new Serializer([$normalizer], [$encoder]);
                $newsfeed = $serializer->normalize($userStories, 'json', [AbstractNormalizer::ATTRIBUTES => ['id', 'feed' => ['id', 'name', 'website'], 'story' => ['id', 'title', 'url', 'content', 'date'], 'starred', 'readStatus']]);
            }
        }

        return $newsfeed;
    }

    public function getFeed($user, $feedId)
    {
        $feedIo = \FeedIo\Factory::create()->getFeedIo();
        $feed = $this->feedRepository->find($feedId);
        $stories = $feed->getStories();
        $lastStory = $stories->first();

        $newsList = [];

        $result = null;

        if ($stories->count() > 0) $result = $feedIo->readSince($feed->getRssLink(), new \DateTime($lastStory->getDate()));
        else $result = $feedIo->read($feed->getRssLink());

        foreach ($result->getFeed() as $item) {
            $newsList[] = $this->addStoryToDb($user, $feed, $item);
        }

        $this->em->flush();
        $this->em->clear();

        uasort($newsList, function ($a, $b) {
            return ($a['date'] > $b['date']) ? -1 : 1;
        });

        return $newsList;
    }

    public function getFeeds(User $user)
    {
        $feeds = $user->getFeeds();

        foreach ($feeds as $feed) {
            $feeds[] = array(
                'id' => $feed->getFeed()->getId(),
                'name' => $feed->getFeed()->getName(),
                'website' => $feed->getFeed()->getWebsite()
            );
        }

        return $feeds;
    }

    public function addStoryToDb($user, $feed, $item)
    {
        $story = new Story();
        $story->setTitle($item->getTitle());
        $story->setContent($item->getDescription());
        $story->setUrl($item->getLink());

        $story->setDate(new \DateTime());
        if (!is_null($item->getLastModified())) $story->setDate($item->getLastModified());

        $story->setFeed($feed);
        $feed->addStory($story);

        $this->em->persist($story);

        $userStory = new UserStory();
        $userStory->setUser($user);
        $userStory->setFeed($feed);
        $userStory->setStory($story);
        $userStory->setStarred(false);
        $userStory->setReadStatus(false);
        $user->addUserStory($userStory);

        $this->em->persist($userStory);
    }

}
