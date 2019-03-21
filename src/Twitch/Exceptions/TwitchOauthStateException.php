<?php

namespace App\Twitch\Exceptions;

use App\Twitch\Exceptions\TwitchException;

class TwitchOauthStateException extends TwitchException
{


    public static function notfound()
    {
        return new static("Twitch did not return {state} key in response. Did you really pass this?");
    }

    /**
     * Throws when $_GET['state'] is not equal with
     * the state we included in the payload
     *
     * @return static
     */
    public static function invalidStateOrTampered()
    {
        return new static("Oops! Invalid {state} value or has been tampered!");
    }
}