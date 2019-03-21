<?php

namespace App\Twitch\Streamer;

use Ixudra\Curl\CurlService;
use App\Twitch\Exceptions\NoRecentActivityException;

class Activity
{

    private $curl;

    private $game;

    public function __construct(CurlService $curl)
    {
        $this->curl = $curl;
    }


    /**
     * Get the top activities of the streamer
     *
     * @return $this
     */
    public function top()
    {
        $this->curl = $this->curl->to(getenv('TWITCH_API') . '/kraken/videos/top');

        return $this;
    }

    /**
     * Game played by streamer
     *
     * @param string $game
     * @param int|null $limit   For pagination
     * 
     * @return $this
     * 
     * @todo: Create a separate class for TWITCH related api information
     *        like TWITCH_CLIENT_ID to we can just reuse that class in
     *        all of our classes
     */
    public function game(string $game, int $limit = null)
    {
        $parameters = [
            'game' => $game,
            'client_id' => getenv('TWITCH_CLIENT_ID'),
            'limit'     => $limit
        ];


        $this->curl = $this->curl->withData(array_filter($parameters));

        return $this;
    }

    public function get()
    {
        $response = $this->curl
                         ->returnResponseObject()
                         ->get();
        
        if ($response->status != getenv('HTTP_RESPONSE_SUCCESS')) {
            throw new NoRecentActivityException();
        }

        $response = json_decode($response->content);
        
        return $response->videos;
    }
}