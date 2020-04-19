<?php

namespace App\Controller;

use Exception;
use App\Entity\Feed;
use App\Utils\FeedHandler;
use App\Repository\FeedRepository;
use App\Repository\UserStoryRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/feed")
 */
class FeedController extends AbstractController
{
    private $feedHandler;
    private $feedRepository;
    private $userStoryRepository;
    private $em;
    private $serializer;

    public function __construct(FeedHandler $feedHandler, FeedRepository $feedRepository, UserStoryRepository $userStoryRepository, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->feedHandler = $feedHandler;
        $this->feedRepository = $feedRepository;
        $this->userStoryRepository = $userStoryRepository;
        $this->em = $em;
        $this->serializer = $serializer;
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
            $newsList = $this->feedHandler->getFeeds($this->getUser());

            return new JsonResponse($newsList, 200);
        } catch (Exception $e) {
            return new JsonResponse(\json_encode($e), 403);
        }
    }

    /**
     * @Route("/get/{id}")
     */
    // public function getFeed($id)
    // {
    //     $user = $this->getUser();

    //     try {
    //         $feed = $this->feedRepository->find($id);

    //         $this->feedHandler->processFeed($feed, $user);

    //         $userStories = $this->userStoryRepository->findBy(['feed' => $feed, 'user' => $user], ['date' => 'DESC']);
            
    //         return new JsonResponse($userStories, 200);
    //     } catch (Exception $e) {
    //         return new JsonResponse(\json_encode($e), 403);
    //     }
    // }

    /**
     * @Route("/get/unreadcount", methods={"GET"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function getUnreadFeedsCount()
    {
        $user = $this->getUser();

        $response = null;

        try {
            $feeds = $user->getFeeds();

            if ($feeds->count() > 0) {
                foreach ($feeds as $feed) {
                    $this->feedHandler->processFeed($feed, $user);
                }

                $this->em->flush();
                $this->em->clear();
            }

            $unreadFeeds = $this->userStoryRepository->countUnreadUserstoriesByFeed($user);

            $serializeUnreadFeeds = $this->serializer->serialize($unreadFeeds, 'json', SerializationContext::create()->enableMaxDepthChecks());

            $response = new Response($serializeUnreadFeeds);
            $response->setStatusCode(Response::HTTP_OK);
            $response->headers->set('Content-Type', 'application/json');
        } catch (Exception $e) {
            $response = new JsonResponse($e, 403);
        }

        return $response;
    }
}
