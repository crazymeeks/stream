<?php

namespace App\Twitch\Exceptions;

class TwitchException extends \Exception
{


    public function __construct($message, $code = 500, \Exception $previous = null)
    {
        @error_log($message); # log silently
        parent::__construct($message, $code, $previous);
    }
}