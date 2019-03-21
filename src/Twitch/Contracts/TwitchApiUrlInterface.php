<?php

namespace App\Twitch\Contracts;

interface TwitchApiUrlInterface
{

    /**
     * Set Twitch api base path(e.g https://id.twitch.tv)
     *
     * @param string $path
     * 
     * @return void
     */
    public function setBasePath($path);

    /**
     * Get Twitch api base path
     *
     * @return string
     */
    public function getBasePath(): string;

    /**
     * Get the fully constructed twitch api url
     * 
     * @return string
     */
    public function get(): string;
}