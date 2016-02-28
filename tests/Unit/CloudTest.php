<?php

class CloudTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \Cloud\Cloud
     */
    protected $client;

    public function setUp()
    {
        $config = require dirname(__DIR__) . '/../vendor/rp/conf/auth.php';
        $this->client = new \Cloud\Cloud($config['opencloud']['username'], $config['opencloud']['key']);
    }

    public function testCreateClass()
    {
        $this->assertInstanceOf('\Cloud\Cloud', $this->client);
    }

}