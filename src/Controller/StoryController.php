<?php

namespace App\Controller;

use App\Repository\StoryRepository;
use App\Repository\UserStoryRepository;
use App\Utils\FeedHandler;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * @package App\Controller
 */
class StoryController extends AbstractController
{
    private $feedHandler;
    private $userStoryRepository;
    private $em;
    private $serializer;

    public function __construct(UserStoryRepository $userStoryRepository, FeedHandler $feedHandler, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->userStoryRepository = $userStoryRepository;
        $this->feedHandler = $feedHandler;
        $this->em = $em;
        $this->serializer = $serializer;
    }
    
    /**
     * @Route("/feed/newsfeed/{offset}", defaults={"offset"=0}, methods={"GET"})
     */
    public function getNewsfeed($offset)
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

            // $userStories = $user->getUserStories();
            $userStories = $this->userStoryRepository->findLimitedUserStories($user, $offset);
            // $userStories = $this->userStoryRepository->findBy(['user' => $user], ['story.date' => 'DESC']);

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
