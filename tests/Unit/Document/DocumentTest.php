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
        $object = $this->service->postDocument(self::TEST_CONTAINER_NAME, 'foobar', 'data');
        $this->assertInstanceOf('OpenCloud\ObjectStore\Resource\DataObject', $object);
    }

    public function testPostDocumentWhenContainerDoesNotExist()
    {
        $this->addMockSubscriber($this->makeResponse(null, 201));
        $this->addMockSubscriber($this->makeResponse(null, 404));
        $object = $this->service->postDocument(self::TEST_CONTAINER_NAME, 'foobar', 'data');
        $this->assertFalse($object);
    }

    public function testGetDocument()
    {
        $this->addMockSubscriber($this->makeResponse(null, 201));
        $this->addMockSubscriber($this->makeResponse('b0dffe8254d152d8fd28f3c5e0404a10'));
        $object = $this->service->getDocument(self::TEST_CONTAINER_NAME, 'foobar');
        $this->assertEquals(
            'b0dffe8254d152d8fd28f3c5e0404a10',
            (string) $object->getContent()
        );
    }

    public function testExists()
    {
        $this->addMockSubscriber($this->makeResponse(null, 201));
        $this->addMockSubscriber($this->makeResponse('b0dffe8254d152d8fd28f3c5e0404a10'));
        $this->assertTrue($this->service->documentExists(self::TEST_CONTAINER_NAME, 'foobar'));
    }

    public function testDoesNotExists()
    {
        $this->addMockSubscriber($this->makeResponse(null, 201));
        $this->addMockSubscriber($this->makeResponse(null, 404));
        $this->assertFalse($this->service->documentExists(self::TEST_CONTAINER_NAME, 'foobar'));
    }
}
