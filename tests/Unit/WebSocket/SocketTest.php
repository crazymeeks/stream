<?php

namespace Tests\Unit\WebSocket;

use App\WebSocket\Socket;
use Tests\AbstractTestCase;

class SocketTest extends AbstractTestCase
{

    /**
     * test
     */
    public function it_should_initialize_socket()
    {
        $options = [
            'address' => '192.168.1.27',
            'port'    => '8282'
        ];
        $socket = new Socket($options);
        
        
    }
}