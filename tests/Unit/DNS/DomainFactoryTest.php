<?php

namespace RoundPartner\Test\Unit\Domain;

use OpenCloud\Tests\MockSubscriber;
use RoundPartner\Cloud\Domain\DomainFactory;
use RoundPartner\Tests\CloudTestCase;

class DomainFactoryTest extends CloudTestCase
{

    protected $mockPath = 'DNS';

    public function setUp()
    {
        $this->client = $this->newClient();
        $this->client->addSubscriber(new MockSubscriber());
    }

    public function testCreate()
    {
        $instance = DomainFactory::create($this->client);
        $this->assertInstanceOf('\RoundPartner\Cloud\Domain\Domain', $instance);
    }
}
