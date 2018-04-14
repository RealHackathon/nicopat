<?php

namespace AppBundle\Controller;

use AppBundle\Service\GetLyrics;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $toFind = "";
        $ok = 1;
        if ($request->query) {
            $toFind = $request->query->get('toFind');
            $ok = $request->query->get('ok');
        }
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'ok' => $ok,
            'toFind' => $toFind,
        ]);
    }

    /**
     * @Route("/page2", name="page2")
     * @Method({"GET", "POST"})
     */
    public function page2Action(Request $request, GetLyrics $getLyrics)
    {
        $toFind = $request->request->get('toFind');
        $songs = $getLyrics->search($toFind);
        if (!$songs['ok']) {
            return $this->redirectToRoute('homepage', [
                'ok' => 0,
                'toFind' => $songs['toFind'],
            ]);
        }
        $song = $getLyrics->getById($songs['toFind'][0]->LyricId, $songs['toFind'][0]->LyricChecksum);


        // replace this example code with whatever you need
        return $this->render('default/test.html.twig', [
            'songs' => $songs['toFind'],
            'song' => $song,
            'lyric' => $song->Lyric
        ]);
    }

    /**
     * @Route("/api/songs/{toFind}", name="songs")
     * @Method({"GET", "POST"})
     */
    public function apiSongsAction(Request $request, GetLyrics $getLyrics, $toFind)
    {
//        if ($request->request ) {
//            $toFind = $request->request->get('toFind');
//        }

        $songs = $getLyrics->apiSearch($toFind);
        $reponse = new JsonResponse($songs);
        $reponse->headers->set('Content-Type', 'application/json');
        return $reponse;
    }

    /**
     * @Route("/api/id/{lyricId}/{lyricChecksum}", name="lines")
     * @Method({"GET", "POST"})
     */
    public function apiIdAction(Request $request, GetLyrics $getLyrics, $lyricId, $lyricChecksum)
    {
//        if ($request->request ) {
//            $lyricId = $request->request->get('lyricId');
//            $lyricChecksum = $request->request->get('lyricChecksum');
//        }


        $lines = $getLyrics->apiGetById($lyricId, $lyricChecksum);
        $reponse = new JsonResponse($lines);
        $reponse->headers->set('Content-Type', 'application/json');
        return $reponse;
    }






}
