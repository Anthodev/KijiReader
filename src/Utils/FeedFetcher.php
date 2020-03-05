<?php

namespace App\Utils;

use App\Entity\User;
use App\Entity\Story;
use App\Repository\FeedRepository;
use App\Repository\StoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class FeedFetcher {

    private $feedRepository;
    private $storyRepository;
    private $em;

    public function __construct(FeedRepository $feedRepository, StoryRepository $storyRepository, EntityManagerInterface $em)
    {
        $this->feedRepository = $feedRepository;
        $this->storyRepository = $storyRepository;
        $this->em = $em;
    }

    public function getNewsfeed(User $user)
    {
        $newsfeed = [];

        $feedIo = \FeedIo\Factory::create()->getFeedIo();
        $feeds = $user->getFeeds($user);

        foreach ($feeds as $feed) {
            $stories = $feed->getStories();
            $lastStory = $stories->first();

            foreach ($stories as $story) {
                $date = new \DateTime();
                $dateDiff = $date->diff($story->getDate())->format('%H');

                if($dateDiff != 0) $dateDiff = $date->diff($story->getDate())->format('%Hh%im');
                else $dateDiff = $date->diff($story->getDate())->format('%i minutes');

                $newsfeed[] = array(
                    'title' => $story->getTitle(),
                    'description' => $story->getContent(),
                    'url' => $story->getUrl(),
                    'date' => $story->getDate(),
                    'dateDiff' => $dateDiff,
                    'read' => $story->getSeen(),
                    'starred' => $story->getStarred(),
                    'feed_id' => $story->getFeed()->getId(),
                    'feed_name' => $story->getFeed()->getName(),
                    'feed_website' => $story->getFeed()->getWebsite()
                );
            }

            $result = null;

            if ($stories->count() > 0) $result = $feedIo->readSince($feed->getRssLink(), $lastStory->getDate());
            else $result = $feedIo->read(($feed->getRssLink()));

            foreach ($result->getFeed() as $item) {
                $newsfeed[] = $this->addStory($feed, $item);
            }
        }

        $this->em->flush();
        $this->em->clear();

        uasort($newsfeed, function ($a, $b) {
            return ($a['date'] > $b['date']) ? -1 : 1;
        });

        return $newsfeed;
    }

    public function getFeed($feedId)
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
            $newsList[] = $this->addStory($feed, $item);
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

    public function addStory($feed, $item)
    {
        $story = new Story();
        $story->setTitle($item->getTitle());
        $story->setContent($item->getDescription());
        $story->setUrl($item->getLink());
        $story->setDate($item->getLastModified());
        $story->setFeed($feed);
        $feed->addStory($story);

        $this->em->persist($story);

        $currentStory = array(
            'title' => $story->getTitle(),
            'description' => $story->getContent(),
            'url' => $story->getUrl(),
            'date' => $story->getDate(),
            'read' => $story->getSeen(),
            'starred' => $story->getStarred(),
            'feed_id' => $story->getFeed()->getId(),
            'feed_name' => $story->getFeed()->getName(),
            'feed_website' => $story->getFeed()->getWebsite()
        );

        return $currentStory;
    }

}
