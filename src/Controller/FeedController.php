<?php

namespace App\Controller;

use App\Entity\Feed;
use App\Entity\Story;
use App\Repository\FeedRepository;
use App\Repository\StoryRepository;
use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/feed")
 */
class FeedController extends AbstractController
{
    /**
     * @Route("/feed", name="feed")
     */
    public function index()
    {
        return $this->render('feed/index.html.twig', [
            'controller_name' => 'FeedController',
        ]);
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, FeedRepository $feedRepository, EntityManager $em)
    {
        $feedUrl = '';
        
        $data = $request->getContent();

        if(!empty($data)) {
            $decodedData = \json_decode($data, true);

            $feedUrl = $decodedData['feedUrl'];
        }

        $feed = $feedRepository->findOneByRssLink($feedUrl);

        if (is_null($feed)) {
            $feedIo = \FeedIo\Factory::create()->getFeedIo();
            $result = $feedIo->read($feedUrl);

            $feed = new Feed();

            $feed->setName($result->getFeed()->getTitle());
            $feed->setRssLink($feedUrl);
            $feed->setWebsite($result->getFeed()->getUrl());

            $em->persist($feed);

            foreach ($result->getFeed() as $news) {
                $story = new Story();
                $story = $this->addNews($news, $story);
                $story->setFeed($feed);
                $em->persist($story);
            }

            $em->flush();

            return new JsonResponse($feed, 200);
        } else {
            return new JsonResponse([
                'message' => 'Feed already registered'
            , 403]);
        }
    }

    /**
     * @Route("/get")
     */
    public function getFeeds(FeedRepository $feedRepository, StoryRepository $storyRepository, EntityManager $em)
    {
        $newsList = [];
        
        $feedIo = \FeedIo\Factory::create()->getFeedIo();
        $feeds = $feedRepository->findAll();

        foreach ($feeds as $feed) {
            $stories = $storyRepository->findBy(['feed' => $feed], ['date' => 'DESC']);
            $newsList[] = $stories;
            $lastStory = $stories->first();
            $result = $feedIo->readSince($feed->getRssLink(), new \DateTime($lastStory->getDate()));

            foreach ($result->getFeed() as $news) {
                $story = new Story();
                $story = $this->addNews($news, $story);
                $story->setFeed($feed);
                $em->persist($story);

                $newsList[] = $story;
            }

            $em->flush();
        }

        $iterator = $newsList->getIterator();

        $iterator->uasort(function ($a, $b) {
            return ($a->getDate() > $b->getDate()) ? -1 : 1;
        });

        try {
            $em->flush();

            return new JsonResponse($iterator, 200);
        } catch (Exception $e) {
            return new JsonResponse(\json_encode($e), 403);
        }
    }

    /**
     * @Route("/get/{id}")
     */
    public function getFeed($id, FeedRepository $feedRepository, StoryRepository $storyRepository, EntityManager $em)
    {
        $feedIo = \FeedIo\Factory::create()->getFeedIo();
        $feed = $feedRepository->find($id);
        $stories = $storyRepository->findBy(['feed' => $feed], ['date' => 'DESC']);
        $lastStory = $stories->first();

        $newsList = $stories;

        $result = $feedIo->readSince($feed->getRssLink(), new \DateTime($lastStory->getDate()));

        foreach ($result->getFeed() as $news) {
            $story = new Story();
            $story = $this->addNews($news, $story);
            $story->setFeed($feed);
            $em->persist($story);

            $newsList[] = $story;
        }

        $em->flush();

        $iterator = $newsList->getIterator();

        $iterator->uasort(function ($a, $b) {
            return ($a->getDate() > $b->getDate()) ? -1 : 1;
        });

        try {
            $em->flush();

            return new JsonResponse($iterator, 200);
        } catch (Exception $e) {
            return new JsonResponse(\json_encode($e), 403);
        }
    }

    public function addNews($news, $story)
    {
        $story->setTitle($news->getTitle());
        $story->setContent($news->getDescription());
        $story->setUrl($news->getUrl());
        $story->setDate($news->getLastModified());
    }
}
