<?php

namespace AppBundle\Controller;

use AppBundle\Service\GetLyrics;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

}
