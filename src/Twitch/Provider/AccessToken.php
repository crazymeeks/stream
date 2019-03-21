<?php

namespace App\Twitch\Provider;

class AccessToken
{

    private $token;

    private $data = [];

    public function __construct($token)
    {
        $this->token = $token;
        $this->map($token);
    }

    /**
     * Map the token to its key
     *
     * @param \stdClass $token
     * 
     * @return void
     */
    private function map(\stdClass $token)
    {
        foreach($token as $key => $value){
            $this->set($key, $value);
        }
    }

    /**
     * Map returned response from twitch
     * so we can have the flexibility to get this
     * for later use
     * 
     * @param string $key
     * @param mixed $value
     * 
     * @return void
     */
    private function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Get the mapped token $key
     *
     * @param string $key
     * 
     * @return mixed
     */
    public function getValueOf($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
        @trigger_error(sprintf("Property {%s} not found.", $key));
        
        return null;
    }

    /**
     * Flush|reset data
     *
     * @return void
     */
    public function flush()
    {
        $this->data = [];
    }

    public function __toString()
    {
        return (string) $this->token;
    }
}