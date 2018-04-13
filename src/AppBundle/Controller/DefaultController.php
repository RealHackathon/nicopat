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
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/page2", name="page2")
     * @Method({"GET", "POST"})
     */
    public function testAction(Request $request, GetLyrics $getLyrics)
    {
<<<<<<< HEAD
        $toFind = $request->request->get('tofind');
//        $toFind = "j'ai encore rêvé d'elle";
=======

//        $toFind = "emportés par la foule";
        $toFind = "au clair de la lune";
>>>>>>> service

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
