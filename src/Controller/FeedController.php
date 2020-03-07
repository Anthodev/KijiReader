<?php

namespace App\Controller;

use Exception;
use App\Entity\Feed;
use App\Utils\FeedFetcher;
use App\Repository\FeedRepository;
use App\Repository\StoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/feed")
 */
class FeedController extends AbstractController
{
    private $feedFetcher;
    private $feedRepository;
    private $em;

    public function __construct(FeedFetcher $feedFetcher, FeedRepository $feedRepository, StoryRepository $storyRepository, EntityManagerInterface $em)
    {
        $this->feedFetcher = $feedFetcher;
        $this->feedRepository = $feedRepository;
        $this->storyRepository = $storyRepository;
        $this->em = $em;
    }

    /**
     * @Route("/add", methods={"POST"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function addFeed(Request $request)
    {
        $user = $this->getUser();
        $feedUrl = '';
        
        $data = $request->getContent();

        if(!empty($data)) {
            $decodedData = \json_decode($data, true);

            $feedUrl = $decodedData['feedUrl'];
        }

        $feed = $this->feedRepository->findOneByRssLink($feedUrl);

        if (is_null($feed)) {
            $result = null;

            $feedIo = \FeedIo\Factory::create()->getFeedIo();

            try {
                $result = $feedIo->read($feedUrl);
            } catch (Exception $e) {
                return new JsonResponse($e, 404);
            }

            $feed = new Feed();

            $feed->setName($result->getFeed()->getTitle());
            $feed->setRssLink($feedUrl);
            $feed->setWebsite($result->getFeed()->getLink());
            $feed->addUser($user);
            $user->addFeed($feed);

            $this->em->persist($feed);

            $this->em->flush();

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
    public function getFeeds()
    {
        try {
            $newsList = $this->feedFetcher->getFeeds($this->getUser());

            return new JsonResponse($newsList, 200);
        } catch (Exception $e) {
            return new JsonResponse(\json_encode($e), 403);
        }
    }

    /**
     * @Route("/get/{id}")
     */
    public function getFeed($id)
    {
        $user = $this->getUser();

        try {
            $newsList = $this->feedFetcher->getFeed($user, $id);

            return new JsonResponse($newsList, 200);
        } catch (Exception $e) {
            return new JsonResponse(\json_encode($e), 403);
        }
    }
}
