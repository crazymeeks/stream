<?php

session_start();
require_once __DIR__ . '/vendor/autoload.php';

use App\Twitch\Twitch;
use App\Twitch\Account\Credentials;
use Ixudra\Curl\CurlService as Curl;
use App\Twitch\Resolver\AuthorizationUrl;

$credential = new Credentials();
$curl = new Curl();
$authorization_url = new AuthorizationUrl();

$twitch = new Twitch($credential, $curl,$authorization_url);
try{
  $state = $twitch->getState();
  header("Location: /streamer.php", 302);exit;
}catch(\App\Twitch\Exceptions\TwitchOauthStateException $e){
  require_once __DIR__ . '/login.php';
}

?>