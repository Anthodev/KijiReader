<?php

namespace App\Controller;

use Exception;
use App\Utils\FeedHandler;
use App\Repository\StoryRepository;
use App\Repository\UserStoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * @package App\Controller
 */
class StoryController extends AbstractController
{
    private $feedHandler;
    private $userStoryRepository;
    private $em;

    public function __construct(StoryRepository $storyRepository, UserStoryRepository $userStoryRepository, FeedHandler $feedHandler, EntityManagerInterface $em)
    {
        $this->storyRepository = $storyRepository;
        $this->userStoryRepository = $userStoryRepository;
        $this->feedHandler = $feedHandler;
        $this->em = $em;
    }
    
    /**
     * @Route("/feed/newsfeed/{offset}", defaults={"offset"=0}, methods={"GET"})
     */
    public function getNewsfeed($offset)
    {
        $user = $this->getUser();

        $jsonResponse = null;

        try {
            $feeds = $user->getFeeds();
            $userStories = $this->userStoryRepository->findByUser($user);

            if ($feeds->count() > 0) {
                foreach ($feeds as $feed) {
                    $this->feedHandler->retrieveFeed($feed, $user);
                }

                $this->em->flush();
                $this->em->clear();
                
                $userStories = $user->getUserStories();
            }

            $jsonResponse = new JsonResponse($userStories, 200);
        } catch (Exception $e) {
            $jsonResponse = new JsonResponse(\json_encode($e), 403);
        }

        return $jsonResponse;
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
