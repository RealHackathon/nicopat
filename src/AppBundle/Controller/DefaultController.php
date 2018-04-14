<?php

namespace AppBundle\Controller;

use AppBundle\Service\GetLyrics;
use RecastAI\Client;
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
     * @Route("/api/songs/{toFind}", name="api_songs")
     * @Method({"GET", "POST"})
     */
    public function apiSongsAction(Request $request, GetLyrics $getLyrics, $toFind)
    {
//        if ($request->request ) {
//            $toFind = $request->request->get('toFind');
//        }

        $songs = $getLyrics->apiSearch($toFind);
        $selectedList = [];
        foreach ($songs as $song) {
            $lyrics = $getLyrics->apiGetById($song->LyricId, $song->LyricChecksum);
            $lines = $getLyrics->apiGetLines($toFind, $lyrics->Lyric);
            if ([] != $lines['lines']) {
                $song->Lines = $lines['lines'];
                $song->found = $lines['ok'];
                $selectedList[] = $song;
            }
        }
        $reponse = new JsonResponse($selectedList);
        $reponse->headers->set('Content-Type', 'application/json');
        return $reponse;
    }

    /**
     * @Route("/api/id/{lyricId}/{lyricChecksum}", name="api_id")
     * @Method({"GET", "POST"})
     */
    public function apiIdAction(Request $request, GetLyrics $getLyrics, $lyricId, $lyricChecksum)
    {
//        if ($request->request ) {
//            $lyricId = $request->request->get('lyricId');
//            $lyricChecksum = $request->request->get('lyricChecksum');
//        }


        $song = $getLyrics->apiGetById($lyricId, $lyricChecksum);
        $reponse = new JsonResponse($song);
        $reponse->headers->set('Content-Type', 'application/json');
        return $reponse;
    }

    /**
     * @Route("/api/lines/{toFind}", name="api_lines")
     * @Method({"GET", "POST"})
     */
    public function apiLinesAction(Request $request, GetLyrics $getLyrics, $toFind)
    {
//        if ($request->request ) {
//            $toFind = $request->request->get('toFind');
//        }

        $songs = $getLyrics->apiSearch($toFind);
        if ([] != $songs) {
            $song = $songs[0];
            $lyric = $getLyrics->apiGetById($song->LyricId, $song->LyricChecksum);
            $lines = $getLyrics->apiGetLines($toFind, $lyric->Lyric);
        }
        $reponse = new JsonResponse($lines);
        $reponse->headers->set('Content-Type', 'application/json');
        return $reponse;
    }

}
