<?php

namespace App\Controller;

use App\Repository\FeedRepository;
use Exception;
use App\Utils\FeedHandler;
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
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * @package App\Controller
 */
class StoryController extends AbstractController
{
    private $feedHandler;
    private $feedRepository;
    private $userStoryRepository;
    private $em;
    private $serializer;

    public function __construct(FeedRepository $feedRepository, UserStoryRepository $userStoryRepository, FeedHandler $feedHandler, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->feedRepository = $feedRepository;
        $this->userStoryRepository = $userStoryRepository;
        $this->feedHandler = $feedHandler;
        $this->em = $em;
        $this->serializer = $serializer;
    }
    
    /**
     * @Route("/feed/newsfeed/{offset}", defaults={"offset"=0}, methods={"GET"})
     */
    public function getNewsfeed(Request $request, $offset)
    {
        $user = $this->getUser();

        $response = null;
        $feedId = 0;

        $data = $request->getContent();

        if (!empty($data)) {
            $decodedData = \json_decode($data, true);

            $feedId = $decodedData['feedId'];
        }

        try {
            if ($feedId == 0) {
                $feeds = $user->getFeeds();
                if ($feeds->count() > 0) {
                    foreach ($feeds as $feed) {
                        $this->feedHandler->processFeed($feed, $user);
                    }

                    $this->em->flush();
                    $this->em->clear();
                }
            } else {
                $feed = $this->feedRepository->find($feedId);
                
                $this->feedHandler->processFeed($feed, $user);

                $this->em->flush();
                $this->em->clear();
            }
            
            $userStories = $this->userStoryRepository->findLimitedUserStories($user, $offset);

            $serializeUserStories = $this->serializer->serialize($userStories, 'json', SerializationContext::create()->enableMaxDepthChecks());

            $response = new Response($serializeUserStories);
            $response->setStatusCode(Response::HTTP_OK);
            $response->headers->set('Content-Type', 'application/json');
        } catch (Exception $e) {
            $response = new JsonResponse($e, 403);
        }
        
        return $response;
    }

    /**
     * @Route("/story/markread/{id}", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function setMarkAsRead($id)
    {
        try {
            $userStory = $this->userStoryRepository->find($id);
            $userStory->setReadStatus(true);
            $this->em->flush();

            return new JsonResponse([
                'message' => 'success'
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse(\json_encode($e), 403);
        }
    }
}
