<?php

namespace RoundPartner\Unit;

use RoundPartner\Cloud\Message\MessageService;
use OpenCloud\Tests\MockSubscriber;
use RoundPartner\Cloud\Queue;
use RoundPartner\Tests\CloudTestCase;

class MessageServiceTest extends CloudTestCase
{

    const TEST_QUEUE = 'tasks_dev';

    /**
     * @var MessageService
     */
    protected $service;

    /**
     * @var \RoundPartner\Cloud\Cloud
     */
    public $client;

    public function setUp()
    {
        $this->client = $this->newClient();
        $this->client->addSubscriber(new MockSubscriber());
        $queue = Queue\QueueFactory::create($this->client, 'secret', self::TEST_QUEUE, 'cloudQueues', 'DFW');
        $this->service = new MessageService($queue);
    }

    public function testNewInstance()
    {
        $this->assertInstanceOf('\RoundPartner\Cloud\Message\MessageService', $this->service);
    }

    public function testPost()
    {
        $this->assertTrue($this->service->post($this->getObject()));
    }

    public function testPostFailsOnInvalidObject()
    {
        $this->assertFalse($this->service->post($this->getInvalidObject()));
    }

    public function testGetReturnsArray()
    {
        $this->assertInternalType('array', $this->service->get());
    }

    public function testPostMessage()
    {
        $this->assertTrue($this->service->post($this->getObject()));
    }

    /**
     * @param string $body
     * @param int $status
     *
     * @dataProvider \RoundPartner\Tests\Providers\QueueProvider::message()
     */
    public function testGetReturnsSingleMessage($body, $status)
    {
        $this->addMockSubscriber($this->makeResponse($body, $status));
        $this->assertCount(1, $this->service->get());
    }

    /**
     * @param string $body
     * @param int $status
     *
     * @dataProvider \RoundPartner\Tests\Providers\QueueProvider::messages()
     */
    public function testGetReturnsMultipleMessages($body, $status)
    {
        $this->addMockSubscriber($this->makeResponse($body, $status));
        $this->assertCount(5, $this->service->get());
    }

    private function getObject()
    {
        $object = new \stdClass();
        $object->hash = base64_encode(openssl_random_pseudo_bytes(64));
        return $object;
    }

    private function getInvalidObject()
    {
        $object = new \stdClass();
        $object->fail = function () {
        };
        return $object;
    }
}
