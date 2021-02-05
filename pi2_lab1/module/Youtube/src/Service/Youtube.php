<?php

namespace Youtube\Service;

class Youtube
{
    const DEV_KEY = 'AIzaSyB_s-LXrbjdnWghWb_xHaJ8UsEMYVO0YZw';

    /**
     * @var \Google_Service_YouTube
     */
    private $youtube;

    public function __construct()
    {
        $client = new \Google_Client();
        $client->setDeveloperKey(self::DEV_KEY);

        $this->youtube = new \Google_Service_YouTube($client);
    }

    public function getMostPopular($pageToken)
    {
        try {
            $films = $this->youtube->videos->listVideos('id, snippet, statistics', ['chart' => 'mostpopular', 'pageToken' => $pageToken]);
            //dd($films);
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        return $films;
    }

    public function search($phrase, $pageToken)
    {
        $filmIds = [];

        try {
            $results = $this->youtube->search->listSearch('id', ['type' => 'video', 'q' => $phrase, 'pageToken' => $pageToken]);

            /** @var \Google_Service_YouTube_SearchResult $r */
            foreach ($results as $r) {
                $filmIds[] = $r->getId()->getVideoId();
            }

            $films = $this->youtube->videos->listVideos('id, snippet, statistics', ['id' => implode(', ', $filmIds)]);
            $films->nextPageToken = $results->nextPageToken;
            $films->prevPageToken = $results->prevPageToken;
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        return $films;
    }

    public function getComments($pageToken, $filmId)
    {
        try {
            // dd($filmId);
            $comments = $this->youtube->commentThreads->listCommentThreads('id, replies, snippet', ['maxResults' => 3, 'pageToken' => $pageToken, 'videoId' => $filmId]);
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        return $comments;
    }
}
