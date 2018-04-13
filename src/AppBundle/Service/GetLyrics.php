<?php

namespace AppBundle\Service;

class GetLyrics
{

    public function search(string $toFind)
    {

        $toFind = urlencode($toFind);
        $apiUrl = "http://api.chartlyrics.com/apiv1.asmx/SearchLyricText";
        $uri = "$apiUrl?lyricText=$toFind";

        $response = json_decode(json_encode(simplexml_load_string(file_get_contents($uri))));
        $response = $response->SearchLyricResult;
        array_pop($response);
        return $response;
    }

}