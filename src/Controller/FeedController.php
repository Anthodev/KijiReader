<?php

namespace App\Controller;

use App\Entity\Flux;
use App\Repository\FluxRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/feed")
 */
class FeedController extends AbstractController
{

    private $fluxRepository;
    private $em;
    
    public function __construct(FluxRepository $fluxRepository, EntityManager $em)
    {
        $this->fluxRepository = $fluxRepository;
        $this->em = $em;
    }

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
    public function add(Request $request)
    {
        $feedUrl = '';
        
        $data = $request->getContent();

        if(!empty($data)) {
            $decodedData = \json_decode($data, true);

            $feedUrl = $decodedData['feedUrl'];
        }

        $feed = $this->fluxRepository->findOneByRssLink($feedUrl);

        if (is_null($feed)) {
            $feedIo = \FeedIo\Factory::create()->getFeedIo();
            $result = $feedIo->read($feedUrl);

            $feed = new Flux();

            $feed->setName($result->getFeed()->getTitle());
            $feed->setRssLink($feedUrl);
            $feed->setWebsite($result->getFeed()->getUrl());

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
     * @Route("/get/{id}")
     */
    public function getFeed($feedUrl)
    {
        $newsList = [];
        
        $feedIo = \FeedIo\Factory::create()->getFeedIo();

        $result = $feedIo->read($feedUrl);

        foreach ($result->getFeed() as $news) {
            // TODO
        }
    }
}
