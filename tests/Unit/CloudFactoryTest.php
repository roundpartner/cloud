<?php

namespace RoundPartner\Unit;

use RoundPartner\Cloud\CloudFactory;

class CloudFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $config = require dirname(__DIR__) . '/../vendor/rp/conf/auth.php';
        $instance = CloudFactory::create($config['opencloud']['username'], $config['opencloud']['key'], $config['opencloud']['secret']);
        $this->assertInstanceOf('\RoundPartner\Cloud\Cloud', $instance);
    }
}