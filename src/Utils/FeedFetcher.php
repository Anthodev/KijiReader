<?php

namespace App\Utils;

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

    public function getFeed($feedId)
    {
        $feedIo = \FeedIo\Factory::create()->getFeedIo();
        $feed = $this->feedRepository->find($feedId);
        $stories = $this->storyRepository->findBy(['feed' => $feed], ['date' => 'DESC']);
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

    public function getFeeds()
    {
        $newsList = [];

        $feedIo = \FeedIo\Factory::create()->getFeedIo();
        $feeds = $this->feedRepository->findAll();

        foreach ($feeds as $feed) {
            $stories = $this->storyRepository->findBy(['feed' => $feed], ['date' => 'DESC']);
            $lastStory = $stories->first();

            foreach ($stories as $story) {
                $newsList[] = array(
                    'title' => $story->getTitle(),
                    'description' => $story->getContent(),
                    'url' => $story->getUrl(),
                    'date' => $story->getDate(),
                    'feed_id' => $story->getFeed()->getId(),
                    'feed_name' => $story->getFeed()->getName(),
                    'feed_website' => $story->getFeed()->getWebsite()
                );
            }

            $result = null;

            if ($stories->count() > 0) $result = $feedIo->readSince($feed->getRssLink(), new \DateTime($lastStory->getDate()));
            else $result = $feedIo->read(($feed->getRssLink()));

            foreach ($result->getFeed() as $item) {
                $newsList[] = $this->addStory($feed, $item);
            }
        }

        $this->em->flush();
        $this->em->clear();

        uasort($newsList, function ($a, $b) {
            return ($a['date'] > $b['date']) ? -1 : 1;
        });

        return $newsList;
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
            'feed_id' => $story->getFeed()->getId(),
            'feed_name' => $story->getFeed()->getName(),
            'feed_website' => $story->getFeed()->getWebsite()
        );

        return $currentStory;
    }

}
