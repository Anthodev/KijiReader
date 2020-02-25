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
    public function add(Request $request, FluxRepository $fluxRepository, EntityManager $em)
    {
        $feedUrl = '';
        
        $data = $request->getContent();

        if(!empty($data)) {
            $decodedData = \json_decode($data, true);

            $feedUrl = $decodedData['feedUrl'];
        }

        $feed = $fluxRepository->findOneByRssLink($feedUrl);

        if (is_null($feed)) {
            $feedIo = \FeedIo\Factory::create()->getFeedIo();
            $result = $feedIo->read($feedUrl);

            $feed = new Flux();

            $feed->setName($result->getFeed()->getTitle());
            $feed->setRssLink($feedUrl);
            $feed->setWebsite($result->getFeed()->getUrl());

            $em->persist($feed);
            $em->flush();

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
    public function getFeed($id, FluxRepository $fluxRepository)
    {
        $newsList = [];

        $feedIo = \FeedIo\Factory::create()->getFeedIo();
        $feed = $fluxRepository->find($id);

        $result = $feedIo->read($feed->getRssLink());

        foreach ($result->getFeed() as $news) {
            // TODO
        }
    }
}
