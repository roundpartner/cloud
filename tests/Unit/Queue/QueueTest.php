<?php

namespace RoundPartner\Unit\Queue;

use OpenCloud\Tests\MockSubscriber;
use RoundPartner\Cloud\Queue;
use RoundPartner\Tests\CloudTestCase;

class QueueTest extends CloudTestCase
{

    const TEST_QUEUE = 'tasks_dev';

    /**
     * @var Queue
     */
    protected $service;

    public function setUp()
    {
        $this->client = $this->newClient();
        $this->client->addSubscriber(new MockSubscriber());
        $this->service = Queue\QueueFactory::create($this->client, 'secret', self::TEST_QUEUE, 'cloudQueues', 'DFW');
    }

    public function testCreateInstance()
    {
        $this->assertInstanceOf('RoundPartner\Cloud\Queue', $this->service);
    }

    public function testGetNoMessage()
    {
        $response = $this->service->getMessages();
        $this->assertEmpty($response);
    }

    /**
     * @param string $body
     * @param int $status
     *
     * @dataProvider \RoundPartner\Tests\Providers\QueueProvider::message()
     */
    public function testGetSingleMessage($body, $status)
    {
        $this->addMockSubscriber($this->makeResponse($body, $status));
        $response = $this->service->getMessages();
        $this->assertCount(1, $response);
    }

    /**
     * @param string $body
     * @param int $status
     *
     * @dataProvider \RoundPartner\Tests\Providers\QueueProvider::message()
     */
    public function testGetNoMessageWhenBlocked($body, $status)
    {
        $this->addMockSubscriber($this->makeResponse($body, $status));
        $this->service->block(1);
        $response = $this->service->getMessages();
        $this->assertEmpty($response);
    }

    /**
     * @param string $body
     * @param int $status
     *
     * @dataProvider \RoundPartner\Tests\Providers\QueueProvider::message()
     */
    public function testGetMessageWhenUnblocked($body, $status)
    {
        $this->addMockSubscriber($this->makeResponse($body, $status));
        $this->service->block(1);
        sleep(1);
        $response = $this->service->getMessages();
        $this->assertCount(1, $response);
    }

    public function testGetStats()
    {
        $body = <<<BODY
{
    "messages": {
        "claimed" : 1,
        "total": 1,
        "free" : 1
    }
}
BODY;
        $this->addMockSubscriber($this->makeResponse($body, 200));
        $this->assertObjectHasAttribute('total', $this->service->getStats());
    }

    public function testDeleteQueue()
    {
        $this->addMockSubscriber($this->makeResponse(null, 204));
        $this->assertTrue($this->service->delete());
    }
}
