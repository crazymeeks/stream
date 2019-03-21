<?php

namespace Tests\Unit\Streamer;

use Tests\AbstractTestCase;
use Ixudra\Curl\CurlService;
use App\Twitch\Streamer\Streamer;


class TwitchStreamerTest extends AbstractTestCase
{


    /**
     * @test
     */
    public function it_should_get_live_streamer()
    {

        $curl = new CurlService();
        $streamer_name = 'dreamleague';
        $streamer = new Streamer($curl, $streamer_name);
        $response = $streamer->type('live')->get();

        $this->assertObjectHasAttribute('stream', $response);
        $this->assertEquals('live', $response->stream->stream_type);

    }

    /**
     * @test
     */
    public function it_should_get_recent_activities_of_streamer()
    {
        $curl = new CurlService();
        $streamer_name = 'dreamleague';
        $limit = 10;
        $streamer = new Streamer($curl, $streamer_name);
        $response = $streamer->activity
                             ->top()
                             ->game('Dota 2', 10)
                             ->get();
        $this->assertObjectHasAttribute('title', $response[0]);
    }
}