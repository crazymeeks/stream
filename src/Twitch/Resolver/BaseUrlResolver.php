<?php

namespace App\Twitch\Resolver;

use App\Twitch\Contracts\TwitchApiUrlInterface;

abstract class BaseUrlResolver implements TwitchApiUrlInterface
{

    /**
     * Twitch API url base path
     *
     * @var string
     */
    protected $base_path;


    /**
     * @inheritDoc
     */
    public function setBasePath($path)
    {
        $this->base_path = $path;
    }

    /**
     * @inheritDoc
     */
    public function getBasePath(): string
    {
        return $this->base_path;
    }

}