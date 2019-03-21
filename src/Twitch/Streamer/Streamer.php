<?php

namespace App\Twitch\Streamer;

use Ixudra\Curl\CurlService;
use App\Twitch\Streamer\Activity;

class Streamer
{

    
    

    /**
     * @var Ixudra\Curl\CurlService
     */
    private $curl;

    /**
     * Name of the streamer
     *
     * @var string
     */
    private $streamer_name;

    /**
     * Type of streamer
     *
     * @var string
     */
    private $stream_type;

    /**
     * @var App\Twitch\Streamer\Activity
     */
    public $activity;

    /**
     * Constructor
     *
     * @param Ixudra\Curl\CurlService $curl
     * @param string $streamer_name
     * 
     */
    public function __construct(CurlService $curl, string $streamer_name, Activity $activity = null)
    {
        $this->curl = $curl;
        $this->streamer_name = $streamer_name;
        $this->activity ?? $this->activity = new Activity($curl);
    }

    /**
     * Get the type of stream(e.g live, playlist, all)
     *
     * @param string $type
     * 
     * @return $this
     */
    public function type(string $type)
    {
        $this->stream_type = $type;

        return $this;
        
    }

    /**
     * Get stream type
     *
     * @return strin
     */
    public function getType(): string
    {
        return $this->stream_type;
    }

    /**
     * Execute our curl service to get response from twitch api
     *
     * @return mixed
     */
    public function get()
    {
        /**
         * @todo: refactor, $endpoint must configurable
         */
        $endpoint = getenv('TWITCH_API') . '/kraken/streams/dreamleague';


        $response = $this->curl->to($endpoint)
                               ->withData(
                                   [
                                       'client_id' => getenv('TWITCH_CLIENT_ID'),
                                       'stream_type' => $this->getType(),
                                    ]
                                )
                                ->returnResponseObject()
                                ->get();

        if (!in_array($response->status, [getenv('HTTP_RESPONSE_SUCCESS')])) {
            throw new \Exception('Error getting data from twitch api.');
        }

        $response = json_decode($response->content);
        
        return $response;
    }
}