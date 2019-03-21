<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
$dotenv = Dotenv::create(__DIR__);
$dotenv->load();

use App\WebSocket\Socket;

$options = [
    'address' => '192.168.1.27',
    'port'    => '8282'
];

$socket = new Socket($options);

$socket->getResponse();