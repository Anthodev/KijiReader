<?php

namespace App\Controller;

use App\Repository\StoryRepository;
use App\Repository\UserStoryRepository;
use App\Utils\FeedFetcher;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
    private $feedFetcher;
    private $userStoryRepository;
    private $em;

    public function __construct(StoryRepository $storyRepository, UserStoryRepository $userStoryRepository, FeedFetcher $feedFetcher, EntityManagerInterface $em)
    {
        $this->storyRepository = $storyRepository;
        $this->userStoryRepository = $userStoryRepository;
        $this->feedFetcher = $feedFetcher;
        $this->em = $em;
    }
    
    /**
     * @Route("/feed/newsfeed/{offset}", defaults={"offset"=0}, methods={"GET"})
     */
    public function getNewsfeed($offset)
    {
        $jsonResponse = null;

        try {
            $jsonResponse = new JsonResponse($this->feedFetcher->getNewsfeed($this->getUser(), $offset), 200);
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
