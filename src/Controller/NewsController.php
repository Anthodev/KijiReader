<?php

namespace App\Controller;

use App\Repository\StoryRepository;
use App\Utils\FeedFetcher;
use Exception;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * @package App\Controller
 */
class NewsController extends AbstractController
{
    private $feedFetcher;

    public function __construct(StoryRepository $storyRepository, FeedFetcher $feedFetcher)
    {
        $this->storyRepository = $storyRepository;
        $this->feedFetcher = $feedFetcher;
    }
    
    /**
     * @Route("/feed/newsfeed", methods={"GET"})
     */
    public function getNewsfeed()
    {
        $jsonResponse = null;

        try {
            $jsonResponse = new JsonResponse($this->feedFetcher->getNewsfeed($this->getUser()), 200);
        } catch (Exception $e) {
            $jsonResponse = new JsonResponse(\json_encode($e), 403);
        }

        return $jsonResponse;
    }
}
