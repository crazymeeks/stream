<?php

namespace Tests\Unit\Oauth;

use Tests\AbstractTestCase;

use App\Twitch\Twitch;
use App\Twitch\Account\Credentials;
use Ixudra\Curl\CurlService as Curl;
use App\Twitch\Provider\AccessToken;
use App\Twitch\Resolver\RequestTokenUrl;
use App\Twitch\Resolver\AuthorizationUrl;
// use League\OAuth2\Client\Token\AccessToken;
// use App\Twitch\Adapter\TwitchProviderAdapter;
// use League\OAuth2\Client\Provider\GenericResourceOwner;

class TwitchOAuthTest extends AbstractTestCase
{

    private $curl;

    public function setUp()
    {
        parent::setUp();
        $this->curl = \Mockery::mock(Curl::class);
    }
    

    /**
     * @test
     */
    public function it_should_oauth_authorization_url()
    {

        $state = 'this-must-be-uniquely-generated';

        $credential = new Credentials();
        $authorization_url = new AuthorizationUrl();
        $twitch = new Twitch($credential, $this->curl,$authorization_url);
        $url = $twitch->getAuthorizationUrl($state);
        
        $expected = 'https://id.twitch.tv/oauth2/authorize?response_type=code&state=this-must-be-uniquely-generated&client_id=dl5n2ehohpnthbermtt0hny55a76x0&redirect_uri=http%3A%2F%2Fcrazymeeks-twitch.local&scopes=user%3Aedit+user%3Aread%3Abroadcast+chat%3Aread';

        $this->assertEquals($expected, $url);
    }

    /**
     * @test
     */
    public function it_should_get_oauth_state_after_authentication()
    {
        $state = 'this-must-be-uniquely-generated';
        $_GET['state'] = $state;
        $credential = new Credentials();
        $authorization_url = new AuthorizationUrl();
        $twitch = new Twitch($credential, $this->curl,$authorization_url);
        $url = $twitch->getAuthorizationUrl($state);

        $this->assertEquals($_GET['state'], $twitch->getState());
    }


    /**
     * @test
     */
    public function it_should_get_access_token_when_user_redirected_back_after_oauth_authentication()
    {

        /**
         * Mock return of twitch authorization_code request
         */
        $return = new \stdClass();
        $return->status = getenv('HTTP_RESPONSE_SUCCESS');
        $return->content = 
            json_encode(
                [
                    'access_token' => 'twitch-returned-access-token',
                    'refresh_token' => 'some-random-refresh-token',
                    'expires_in'    => 3600, #1hr
                    'scope' => 'the-previously-listed-scope-generated-during-getting-authorization-code',
                    'token_type' => 'bearer'
                ]
            
        );
        
        $access_token = new AccessToken($return);

        # Fetch code from $_GET['code'] var
        $code = $_GET['code'] = 'this-must-be-twitch-authorization-code';

        $this->curl->shouldReceive('to')
                   ->with('https://id.twitch.tv/oauth2/token')
                   ->andReturnSelf();
        $this->curl->shouldReceive('withData')
                   ->with([
                       'code' => $code,
                       'client_id'     => 'dl5n2ehohpnthbermtt0hny55a76x0',
                       'client_secret' => 'z33h9bhnfvkfqnx5nlrhcous7ubkzc',
                       'redirect_uri'  => 'http://crazymeeks-twitch.local',
                       'grant_type'    => 'authorization_code',
                       
                   ])
                   ->andReturnSelf();
        $this->curl->shouldReceive('returnResponseObject')
                   ->andReturnSelf();
        $this->curl->shouldReceive('post')
                   ->andReturn($return);

        $credential = new Credentials();
        $authorization_url = new RequestTokenUrl();
        $twitch = new Twitch($credential, $this->curl,$authorization_url);
        
        $access_token = $twitch->getAccessToken($code);

        $this->assertEquals('twitch-returned-access-token', $access_token->getValueOf('access_token'));
        $this->assertEquals('some-random-refresh-token', $access_token->getValueOf('refresh_token'));
        $this->assertEquals(3600, $access_token->getValueOf('expires_in'));
        $this->assertEquals('the-previously-listed-scope-generated-during-getting-authorization-code', $access_token->getValueOf('scope'));
        $this->assertEquals('bearer', $access_token->getValueOf('token_type'));
        
        echo "Refactor code here. " . __METHOD__;

    }







    // private $provider_mock;

    // private $access_token_mock;

    // private $resource_owner_mock;

    // public function setUp()
    // {
    //     parent::setUp();

    //     $this->provider_mock = \Mockery::mock(TwitchProviderAdapter::class);

    //     $this->access_token_mock = \Mockery::mock(AccessToken::class);

    //     $this->resource_owner_mock = \Mockery::mock(GenericResourceOwner::class);

    // }

    // /**
    //  * @test
    //  */
    // public function it_should_get_authorization_url()
    // {
        
    //     $credentials = new Credentials();

        

    //     $this->provider_mock->shouldReceive('getAuthorizationUrl')
    //                   ->andReturn('https://id.twitch.tv/oauth2/authorize?state=b122e97fbf7cad18c60215917179af07&amp;scope=user%3Aedit%20user%3Aread%3Abroadcast%20chat%3Aread&amp;response_type=code&amp;approval_prompt=auto&amp;redirect_uri=http%3A%2F%2Fcrazymeeks-twitch.local&amp;client_id=dl5n2ehohpnthbermtt0hny55a76x0');

    
    //     $expected = 'https://id.twitch.tv/oauth2/authorize?state=b122e97fbf7cad18c60215917179af07&amp;scope=user%3Aedit%20user%3Aread%3Abroadcast%20chat%3Aread&amp;response_type=code&amp;approval_prompt=auto&amp;redirect_uri=http%3A%2F%2Fcrazymeeks-twitch.local&amp;client_id=dl5n2ehohpnthbermtt0hny55a76x0';
    //     $this->assertEquals($expected, $this->provider_mock->getAuthorizationUrl());

    // }

    // /**
    //  * @test
    //  */
    // public function it_should_get_twitch_user_access_token()
    // {
    //     //getAccessToken

    //     $_GET['code'] = 'this-must-be-twitch-authorization-code';

    //     $this->provider_mock->shouldReceive('getAccessToken')
    //                         ->with('authorization_code', [
    //                             'code' => $_GET['code']
    //                         ])
    //                         ->andReturn($this->access_token_mock);
        
    //     $this->assertInstanceOf(AccessToken::class, $this->access_token_mock);
    // }

    // /**
    //  * @test
    //  */
    // public function it_should_get_user_information()
    // {

    //     $this->resource_owner_mock->shouldReceive('toArray')
    //                               ->andReturn([
    //                                   'display_name' => 'John',
    //                                   'bio'          => 'Sample bio',
    //                               ]);

    //     $this->provider_mock->shouldReceive('getResourceOwner')
    //                         ->with($this->access_token_mock)
    //                         ->andReturn($this->resource_owner_mock);

    //     $resourceOwner = $this->provider_mock->getResourceOwner($this->access_token_mock);
    //     $this->assertArrayHasKey('display_name', $resourceOwner->toArray());
    // }
}