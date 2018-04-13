<?php

namespace AppBundle\Controller;

use AppBundle\Service\GetLyrics;
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
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/test", name="test")
     */
    public function testAction(Request $request, GetLyrics $getLyrics)
    {

//        $toFind = "emportés par la foule";
        $toFind = "au clair de la lune";

        $songs = $getLyrics->search($toFind);
        $song = $getLyrics->getById($songs[0]->LyricId, $songs[0]->LyricChecksum);


        // replace this example code with whatever you need
        return $this->render('default/test.html.twig', [
            'songs' => $songs,
            'song' => $song,
            'lyric' => $song->Lyric
        ]);
    }

}
