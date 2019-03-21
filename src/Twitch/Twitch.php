<?php

namespace App\Twitch;

use App\Twitch\Provider\AccessToken;
use Ixudra\Curl\CurlService as Curl;
use App\Twitch\Contracts\TwitchApiUrlInterface;
use App\Twitch\Contracts\TwitchCredentialInterface;
use App\Twitch\Exceptions\TwitchOauthStateException;

use App\Twitch\Exceptions\TwitchAccessTokenRequestException;

class Twitch
{

    /**
     * @var App\Twitch\Contracts\TwitchCredentialInterface
     */
    private $credential;

    /**
     * @var Ixudra\Curl\CurlService
     */
    private $curl;
    
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $url;


    public function __construct(TwitchCredentialInterface $credential, Curl $curl, TwitchApiUrlInterface $url)
    {
        $this->credential = $credential;
        $this->curl = $curl;
        $this->url = $url;
    }

    /**
     * Get twitch authorization url
     *
     * @return string authorization url
     * 
     */
    public function getAuthorizationUrl($with_state = null)
    {
        $credential = $this->credential->getCredentials();

        // @todo: refactor this
        $params = compact('credential');
        $params = array_merge(
            [
                'response_type' => 'code',
                'state' => $with_state ?? $with_state = uniqid(), # to avoid csrf attacks
            ],
            $params['credential']
        );
        $this->setStateToSession($with_state);
        unset($params['client_secret']);

        return $this->url->get() . '?' . http_build_query($params, '', '&');
    }

    /**
     * Set the state to PHP's session
     * 
     * We will use this to validate state in PHP's $_GET var later
     *
     * @param string $state
     * 
     * @return void
     */
    private function setStateToSession($state)
    {
        $_SESSION['oauth_state'] = $state;
    }

    /**
     * Get unique state token
     *
     * @return string|null
     * 
     * @throws App\Twitch\Exceptions\TwitchOauthStateException
     */
    public function getState()
    {

        
        if (isset($_SESSION['oauth_state'])) {
            return $_SESSION['oauth_state'];
        }

        if (!isset($_GET['state'])) {
            throw TwitchOauthStateException::notfound();
        }

        if (isset($_GET['state']) && $_GET['state'] != $_SESSION['oauth_state']) {
            throw TwitchOauthStateException::invalidStateOrTampered();
        }

        
    }

    /**
     * Request|Get access token from twitch
     * 
     * This is based on the authorization code generated
     * when user redirected back after oauth authentication
     *
     * @param string $code
     * 
     * @return mixed
     */
    public function getAccessToken(string $code)
    {

        /**
         * @todo:: Refactor this
         */
        $credential = $this->credential->getCredentials();
        $params = compact('credential');
        $params = array_merge(
            [
                'code' => $code,
                'grant_type' => 'authorization_code',
            ],
            $params['credential']
        );

        unset($params['scopes']);
        
        $response = $this->curl->to($this->url->get())
                               ->withData($params)
                               ->returnResponseObject()
                               ->post();
        if ($response->status != getenv('HTTP_RESPONSE_SUCCESS')) {
            throw new TwitchAccessTokenRequestException();
        }
        
        return new AccessToken(json_decode($response->content));
        
    }

}