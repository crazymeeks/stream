<?php
session_start();
#unset($_SESSION['oauth_state']);
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
  $twitch->getState();
}catch(\App\Twitch\Exceptions\TwitchOauthStateException $e){
  header("Location: /");exit;
}
?>
<html>
    <head>
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    </head>
  <body>
  
    <!-- Add a placeholder for the Twitch embed -->
    <div id="twitch-embed"></div>

    <!-- Load the Twitch embed script -->
    <script src="https://embed.twitch.tv/embed/v1.js"></script>

    <!-- Create a Twitch.Embed object that will render within the "twitch-embed" root element. -->
    <script type="text/javascript">
      new Twitch.Embed("twitch-embed", {
        width: 854,
        height: 480,
        channel: "dreamleague"
      });
    </script>
    
    <div class="row">
      <div class="col-md-6">
        <div class="alert alert-success">Recent events:</div>
            <div id="content"></div>
            <script type="text/javascript">
                const host = 'ws://192.168.1.27:8282/socket.php';
                const socket = new WebSocket(host);
                let c = 0;
                socket.onmessage = (e) => {
                    if (c > 9) {
                        c = 0;
                        document.getElementById('content').innerHTML = e.data
                    } else {
                        document.getElementById('content').innerHTML += e.data;
                    }
                    c++;
                };
            </script>
      </div>
    </div>
  </body>
</html>