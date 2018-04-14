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
        if (0 == strlen($toFind)) {
            return ['ok' => false, 'toFind' => $toFind];
        }
        $toFindEncoded = urlencode(htmlspecialchars($toFind));
        $apiUrl = self::BASE_URL . "/SearchLyricText";
        $uri = "$apiUrl?lyricText=$toFindEncoded";

        $response = $this->xmlToObject(file_get_contents($uri));
        $response = $response->SearchLyricResult;
        if (!is_array($response) || 0 == count($response)) {
            return ['ok' => false, 'toFind' => $toFind];
        }
        array_pop($response);
        return ['ok'=> true, 'toFind' => $response];
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