<?php

namespace App\Entity\Tests;

use App\Entity\Feed;
use App\Entity\User;
use App\Utils\FeedHandler;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FeedHandlerTest extends KernelTestCase
{
    protected $feedHandler;
    protected $em;
    protected $feedIo;

    protected function setUp() : void
    {
        $kernel = self::bootKernel();
        $this->em = $kernel->getContainer()->get('doctrine')->getManager();
        $this->feedIo = $kernel->getContainer()->get('feedio');
        $this->feedHandler = new FeedHandler($this->em, $this->feedIo);
        parent::setUp();
    }

    /**
     * @return mixed 
     * @throws ExpectationFailedException
     */
    public function testGetFeed()
    {
        $feedUrl = 'https://framablog.org/feed/';

        $newsfeed = $this->feedHandler->getFeed($feedUrl);

        $this->assertNotEmpty($newsfeed);

        return $newsfeed;
    }

    /**
     * @depends testGetFeed
     * @return Feed 
     * @throws ExpectationFailedException
     */
    public function testFetchFeed($feedResource)
    {
        // $feedUrl = 'http://www.jeuxvideo.com/rss/rss.xml';
        // $result = null;
        
        // $result = $this->feedHandler->getFeed($feedUrl);

        $feed = new Feed();
        $feed->setName($feedResource->getFeed()->getTitle());
        $feed->setRssLink($feedResource->getUrl());
        // $feed->setRssLink($feedUrl);
        
        $this->assertNotEmpty($feedResource->getFeed()->getTitle());

        return $feed;
    }

    /**
     * @depends testGetFeed
     * @return void 
     * @throws ExpectationFailedException
     */
    public function testFetchFeedWithDate($feedResource)
    {
        $result = $this->feedHandler->getFeed($feedResource->getUrl(), new \DateTime('-7 days'));

        $this->assertNotEmpty($result->getFeed()->getTitle());
    }

    /**
     * @depends testFetchFeed
     */
    public function testAddStory($feed)
    {
        $storyData = null;
        
        $feedData = $this->feedHandler->getFeed($feed->getRssLink());

        foreach ($feedData->getFeed() as $item) {
            $storyData = $this->feedHandler->addStory($feed, $item);
        }

        $this->assertNotEmpty($storyData);
    }

    /**
     * @depends testFetchFeed
     * @return void 
     */
    public function testRetrieveFeed($feed)
    {
        $user = new User();
        $user->setUsername('Test');
        $user->setEmail('test@oc.io');

        $user->addFeed($feed);
        $feed->addUser($user);

        $userStories = $this->feedHandler->retrieveFeed($feed, $user);
        $this->em->clear();

        $this->assertNotNull($userStories);
    }
}
