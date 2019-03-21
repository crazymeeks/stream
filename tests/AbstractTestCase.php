<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use Dotenv\Dotenv;

class AbstractTestCase extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        $dotenv = Dotenv::create(__DIR__ .'/../');
        $dotenv->load();
    }


    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
}