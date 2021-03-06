<?php

namespace AppBundle\Service;

use Fuse\Fuse;

class GetLyrics
{
    const BASE_URL = 'http://api.chartlyrics.com/apiv1.asmx/';

    private function xmlToObject(string $xmlString)
    {
        $response = simplexml_load_string($xmlString);
        $response = json_encode($response);
        return json_decode($response);
    }

    /**
     * @param string $toFind
     * @return mixed
     */
    public function apiSearch(string $toFind)
    {
        if (0 == strlen($toFind)) {
            return [];
        }
        $toFind = str_replace("'", " ", $toFind);
        $keyWords = explode(' ',$toFind);
        $toFind = "";
        $score = [];
        foreach( $keyWords as $keyWord) {
            if (strlen($keyWord) > 1) {
                $score[$keyWord] = 0;
                if (strlen($toFind) > 0) {
                    $toFind .= '+';
                }
                $toFind .= $keyWord;
            }
        }
        $toFindEncoded = htmlspecialchars($toFind);
        $apiUrl = self::BASE_URL . "/SearchLyricText";
        $uri = "$apiUrl?lyricText=$toFindEncoded";
        $songs = $this->xmlToObject(file_get_contents($uri));
        $songs = $songs->SearchLyricResult;
        if (!is_array($songs) || 0 == count($songs)) {
            return [];
        }
        array_pop($songs);
        $response = [];
        for ($i=0; $i<10; $i++) {
            if (isset($songs[$i])) {
                $response[] = $songs[$i];
            }
        }
        return $response;
    }

    public function apiGetById($lyricId, $lyricChecksum)
    {
        $apiUrl = self::BASE_URL . "/GetLyric?";
        $songQuery = http_build_query(compact('lyricId', 'lyricChecksum'));
        $uri = $apiUrl . $songQuery;
        $contents = file_get_contents($uri);
        $response = $this->xmlToObject($contents);
        return $response;
    }

    public function apiGetLines(string $searchTerm, string $lyric)
    {
        $keyWords = explode(' ', str_replace("'", " ", $searchTerm));
        $ok = false;
        foreach( $keyWords as $keyWord) {
            if (strlen($keyWord) > 1) {
                $score[strtoupper($keyWord)] = 0;
            }
        }
        $lyrics = preg_split('/\n/', $lyric);
        $lyrics = array_reduce($lyrics, function ($acc, $item) {
            if (trim($item)) {
                $acc[] = $item;
            }
            return $acc;
        }, []);
        $fuse = new Fuse($lyrics);
        $results = $fuse->search($searchTerm);
        $lines = [];
        if (count($results)>0) {
            $lineNumber = $results[0];
            $max = min($lineNumber + 3, count($lyrics));
            for ($i=$lineNumber; $i < $max; $i++) {
                $lines[] = $lyrics[$i];
                $found = true;
                foreach ($score as $key => $value) {
                    $found = $found && (preg_match('/'.$key.'/', strtoupper($lyrics[$i])) > 0);
                }
                $ok = $ok || $found;
            }
        }
        return ['ok' => $ok, 'lines' => $lines];
    }

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

}