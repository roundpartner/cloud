<?php

namespace RoundPartner\Unit\Document;

use OpenCloud\Tests\OpenCloudTestCase;
use RoundPartner\Cloud\Service\Cloud;
use RoundPartner\Cloud\Document\Document;
use RoundPartner\Conf\Service;

class DocumentTest extends OpenCloudTestCase
{

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
        $config = Service::get('opencloud');
        $this->client = new Cloud($config['username'], $config['key']);
        $this->service = new Document($this->client);
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
