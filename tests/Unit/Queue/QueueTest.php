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

    public function testGetSingleMessage()
    {
        $body = <<<BODY
[
    {
      "body": {
        "event": "BackupStarted"
      },
      "age": 296,
      "href": "/v1/queues/demoqueue/messages/51db6f78c508f17ddc924357?claim_id=51db7067821e727dc24df754",
      "ttl": 300
    }
]
BODY;

        $this->addMockSubscriber($this->makeResponse($body, 200));
        $response = $this->service->getMessages();
        $this->assertCount(1, $response);
    }

    public function testGetNoMessageWhenBlocked()
    {
        $body = <<<BODY
[
    {
      "body": {
        "event": "BackupStarted"
      },
      "age": 296,
      "href": "/v1/queues/demoqueue/messages/51db6f78c508f17ddc924357?claim_id=51db7067821e727dc24df754",
      "ttl": 300
    }
]
BODY;

        $this->addMockSubscriber($this->makeResponse($body, 200));
        $this->service->block(1);
        $response = $this->service->getMessages();
        $this->assertEmpty($response);
    }

    public function testGetMessageWhenUnblocked()
    {
        $body = <<<BODY
[
    {
      "body": {
        "event": "BackupStarted"
      },
      "age": 296,
      "href": "/v1/queues/demoqueue/messages/51db6f78c508f17ddc924357?claim_id=51db7067821e727dc24df754",
      "ttl": 300
    }
]
BODY;

        $this->addMockSubscriber($this->makeResponse($body, 200));
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
