<?php

namespace RoundPartner\Test\Unit;

use RoundPartner\Cloud\CloudFactory;

class CloudFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $instance = CloudFactory::create('username', 'key', 'secret');
        $this->assertInstanceOf('\RoundPartner\Cloud\Cloud', $instance);
    }
}
