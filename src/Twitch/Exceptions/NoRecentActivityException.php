<?php

namespace App\Twitch\Exceptions;

use App\Twitch\Exceptions\TwitchException;

class NoRecentActivityException extends TwitchException
{

    public function __construct($message = null)
    {
        $message ?? $message = "Streamer has no recent activities.";
        parent::__construct($message);
    }
}