<?php

namespace App\Twitch\Contracts;

interface TwitchCredentialInterface
{


    /**
     * Get twitch client id and secret key
     *
     * @return array
     */
    public function getCredentials(): array;


}