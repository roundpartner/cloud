<?php

namespace RoundPartner\Unit;

use RoundPartner\Cloud\CloudFactory;
use RoundPartner\Conf\Service;

class CloudFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $config = Service::get('opencloud');
        $instance = CloudFactory::create($config['username'], $config['key'], $config['secret']);
        $this->assertInstanceOf('\RoundPartner\Cloud\Cloud', $instance);
    }
}
