<?php

namespace App\WebSocket;

use Ixudra\Curl\CurlService;
use App\Twitch\Streamer\Streamer;

class Socket
{
    
    const OPTVAL = 1;
    const TYPE = 5000;

    private $socket;

    public function __construct(array $options)
    {
        $this->initialize($options);
    }

    protected function initialize(array $options)
    {
        $server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, self::OPTVAL);
        socket_bind($server, $options['address'], $options['port']);
        socket_listen($server);

        $this->socket = socket_accept($server);

        $this->makeHandShake($this->socket);
        
    }

    private function makeHandShake($socket)
    {
        $request = socket_read($socket, self::TYPE);

        preg_match('#Sec-WebSocket-Key: (.*)\r\n#', $request, $matches);

        $key = base64_encode(pack('H*', sha1($matches[1] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));

        $headers = "HTTP/1.1 101 Switching Protocols\r\n";
        $headers .= "Upgrade: websocket\r\n";
        $headers .= "Connection: Upgrade \r\n";
        $headers .= "Sec-WebSocket-Version: 13\r\n";
        $headers .= "Sec-WebSocket-Accept: $key\r\n\r\n";
        socket_write($socket, $headers, strlen($headers));

    }

    public function getResponse()
    {

        $curl = new CurlService();
        $streamer_name = 'dreamleague';
        $limit = 10;
        
        $streamer = new Streamer($curl, $streamer_name);
        $activities = $streamer->activity
                            ->top()
                            ->game('Dota 2', $limit)
                            ->get();
        while(true){
            sleep(5);
            foreach($activities as $activity){
                $activity = chr(129) . chr(strlen($activity->title)) . $activity->title;
                socket_write($this->getSocket(), $activity);
                unset($activity);
            }
            

        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getSocket()
    {
        return $this->socket;
    }
}