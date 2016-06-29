<?php

namespace RoundPartner\Unit\Document;

use OpenCloud\Tests\MockSubscriber;
use RoundPartner\Cloud\Document\Document;
use RoundPartner\Tests\CloudTestCase;

class DocumentTest extends CloudTestCase
{

    /**
     * @const string
     */
    const TEST_CONTAINER_NAME = 'test_container';

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
        $this->client->addSubscriber(new MockSubscriber());
        $this->service = new Document($this->client, 'DFW');
    }

    public function testCreateInstance()
    {
        $this->assertInstanceOf('RoundPartner\Cloud\Document\Document', $this->service);
    }

    public function testGetContainer()
    {
        $this->addMockSubscriber($this->makeResponse(null, 201));
        $this->assertInstanceOf('OpenCloud\ObjectStore\Resource\Container', $this->service->getContainer(self::TEST_CONTAINER_NAME));
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
        $this->assertFalse($this->service->containerExists(self::TEST_CONTAINER_NAME));
    }

    public function testPostDocument()
    {
        $this->addMockSubscriber($this->makeResponse(null, 201));
        $this->addMockSubscriber($this->makeResponse(null, 201));
        $result = $this->service->postDocument(self::TEST_CONTAINER_NAME, 'foobar', 'data');
        $this->assertInstanceOf('OpenCloud\ObjectStore\Resource\DataObject', $result);
    }

    public function testPostDocumentWhenContainerDoesNotExist()
    {
        $this->addMockSubscriber($this->makeResponse(null, 201));
        $this->addMockSubscriber($this->makeResponse(null, 404));
        $result = $this->service->postDocument(self::TEST_CONTAINER_NAME, 'foobar', 'data');
        $this->assertFalse($result);
    }
}
