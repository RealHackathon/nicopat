<?php

namespace AppBundle\Service;

class GetLyrics
{
    const BASE_URL = 'http://api.chartlyrics.com/apiv1.asmx/';

    /**
     * @param string $toFind
     * @return mixed
     */
    public function search(string $toFind)
    {

        $toFind = urlencode($toFind);
        $apiUrl = self::BASE_URL . "/SearchLyricText";
        $uri = "$apiUrl?lyricText=$toFind";

        $response = $this->xmlToObject(file_get_contents($uri));
        $response = $response->SearchLyricResult;
        if (count($response) > 0)
            array_pop($response);
        return $response;
    }

    public function getById($lyricId, $lyricChecksum)
    {
        $apiUrl = self::BASE_URL . "/GetLyric?";
        $songQuery = http_build_query(compact('lyricId', 'lyricChecksum'));

        $uri = $apiUrl . $songQuery;

        $contents = file_get_contents($uri);
        $response = $this->xmlToObject($contents);

        return $response;

    }

    public function getALine($search, $lyric)
    {

    }

    private function xmlToObject(string $xmlString)
    {
        $response = simplexml_load_string($xmlString);
        $response = json_encode($response);
        return json_decode($response);

    }

}