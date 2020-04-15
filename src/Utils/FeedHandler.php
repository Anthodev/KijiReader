<?php

namespace App\Utils;

use App\Entity\Feed;
use App\Entity\User;
use App\Entity\Story;
use App\Entity\UserStory;
use Doctrine\ORM\EntityManagerInterface;
use FeedIo\FeedIo;

class FeedHandler
{
    private $em;
    private $feedIo;

    public function __construct(EntityManagerInterface $em, FeedIo $feedIo)
    {
        $this->em = $em;
        $this->feedIo = $feedIo;
    }

    public function getFeed($feedUrl, $date = null)
    {
        $feed = null;
      
        if (!is_null($date)) $feed = $this->feedIo->readSince($feedUrl, $date);
        else $feed = $this->feedIo->read($feedUrl);

        return $feed;
    }

    public function getFeeds(User $user)
    {
        $feeds = $user->getFeeds();

        return $feeds;
    }

    public function addStory($feed, $item)
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
        $this->em->persist($feed);
      
        return $story;
    }

    public function addUserStory($user, $feed, $story)
    {
        $userStory = new UserStory();
        $userStory->setUser($user);
        $userStory->setFeed($feed);
        $userStory->setStory($story);
        $userStory->setStarred(false);
        $userStory->setReadStatus(false);
        $user->addUserStory($userStory);

        $this->em->persist($userStory);
        $this->em->persist($user);

        return $userStory;
    }

    public function processFeed(Feed $feed, User $user)
    {
        $stories = $feed->getStories();
        $lastStory = $stories->first();
        $result = null;

        if ($stories->count() > 0) $result = $this->getFeed($feed->getRssLink(), $lastStory->getDate());
        else $result = $this->getFeed($feed->getRssLink());
      
        if (!is_null($result)) {
            foreach ($result->getFeed() as $item) {
                $story = $this->addStory($feed, $item);
                $this->addUserStory($user, $feed, $story);
            }
        }
    }
}
