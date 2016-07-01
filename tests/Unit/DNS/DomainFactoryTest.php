<?php

namespace RoundPartner\Test\Unit\Domain;

use RoundPartner\Cloud\Domain\DomainFactory;
use RoundPartner\Cloud\Service\Cloud;
use RoundPartner\Conf\Service;
use RoundPartner\Tests\CloudTestCase;

class DomainFactoryTest extends CloudTestCase
{

    protected $mockPath = 'DNS';

    public function testCreate()
    {
        $config = Service::get('opencloud');
        $client = new Cloud($config['username'], $config['key']);
        $instance = DomainFactory::create($client);
        $this->assertInstanceOf('\RoundPartner\Cloud\Domain\Domain', $instance);
    }
}
