<?php

namespace RoundPartner\Unit\Document;

use OpenCloud\Tests\MockSubscriber;
use OpenCloud\Tests\OpenCloudTestCase;
use RoundPartner\Cloud\Service\Cloud;
use RoundPartner\Cloud\Document\Document;
use RoundPartner\Conf\Service;

class DocumentTest extends OpenCloudTestCase
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
        $config = Service::get('opencloud');
        $this->client = new Cloud($config['username'], $config['key']);
        $this->config = Service::get('testclouddocument');
        $this->service = new Document($this->client);
    }

    public function testCreateInstance()
    {
        $this->assertInstanceOf('RoundPartner\Cloud\Document\Document', $this->service);
    }

    public function testGetContainer()
    {
        $this->addMockSubscriber($this->makeResponse(null, 201));
        $this->assertInstanceOf('OpenCloud\ObjectStore\Resource\Container', $this->service->getContainer($this->config['name']));
    }

    public function testGetContainerThrowsExceptionOnError()
    {
        $this->addMockSubscriber($this->makeResponse(null, 404));
        $this->setExpectedException('OpenCloud\Common\Exceptions\InvalidArgumentError');
        $this->service->getContainer('this/will/throw/an/exception');
    }

    public function testContainerExists()
    {
        $this->addMockSubscriber($this->makeResponse(null, 404));
        $this->assertFalse($this->service->containerExists($this->config['name']));
    }
}
