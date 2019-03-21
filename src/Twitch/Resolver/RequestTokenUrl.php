<?php

namespace App\Twitch\Resolver;

use App\Twitch\Resolver\BaseUrlResolver;

class RequestTokenUrl extends BaseUrlResolver
{

    const BASE_PATH = 'https://id.twitch.tv';

    private $slug;


    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getSlug()
    {
        return $this->slug ?? $this->slug = '/oauth2/token';
    }


    /**
     * @inheritDoc
     */
    public function getBasePath(): string
    {
        return $this->base_path ?? $this->base_path = 'https://id.twitch.tv';
    }

    /**
     * @inheritDoc
     */
    public function get(): string
    {
        return rtrim($this->getBasePath(), '/') . '/' . ltrim($this->getSlug(), '/');   
    }

}