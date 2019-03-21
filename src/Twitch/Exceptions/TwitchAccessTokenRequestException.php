<?php

namespace App\Twitch\Exceptions;

use App\Twitch\Exceptions\TwitchException;

class TwitchAccessTokenRequestException extends TwitchException
{

    public function __construct($message = null)
    {
        $message ?? $message = 'Error when trying to get access token with Twitch api.';
        parent::__construct($message);
    }
}