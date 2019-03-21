<?php

namespace App\Twitch\Account;

use App\Twitch\Contracts\TwitchCredentialInterface;

class Credentials implements TwitchCredentialInterface
{

    /**
     * @inheritDoc
     */
    public function getCredentials(): array
    {
        $scopes = is_array($this->scopes()) ? implode(' ', $this->scopes())
                                            : $this->scopes();
        return [
            'client_id'       => 'dl5n2ehohpnthbermtt0hny55a76x0',     // The client ID assigned when you created your application
            'client_secret'   => 'z33h9bhnfvkfqnx5nlrhcous7ubkzc',
            'redirect_uri'    => 'http://crazymeeks-twitch.local',  // Your redirect URL you specified when you created your application
            'scopes'          =>   $scopes, // The scopes you would like to request
        ];
    }

    public function scopes()
    {
        return implode(' ', ['user:edit', 'user:read:broadcast', 'chat:read']);
    }
}