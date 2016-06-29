<?php

namespace RoundPartner\Unit\Document;

use OpenCloud\Tests\MockSubscriber;
use RoundPartner\Cloud\Document\DocumentFactory;
use RoundPartner\Cloud\Document\Document;
use RoundPartner\Tests\CloudTestCase;

class DocumentFactoryTest extends CloudTestCase
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var Document
     */
    protected $service;

    public function setUp()
    {
        $this->client = $this->newClient();
        $this->getClient()->addSubscriber(new MockSubscriber());
        $this->service = DocumentFactory::create($this->getClient(), 'DFW');
    }

    public function testCreateInstance()
    {
        $this->assertInstanceOf('RoundPartner\Cloud\Document\Document', $this->service);
    }
}
