<?php

namespace RoundPartner\Unit\Queue;

use RoundPartner\Cloud\Queue\MultiQueue;
use RoundPartner\Tests\Mock\QueueMock;

class MultiQueueTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var MultiQueue
     */
    protected $service;

    public function setUp()
    {
        $this->service = new MultiQueue();
    }

    public function testCreateInstance()
    {
        $this->assertInstanceOf('RoundPartner\Cloud\Queue\MultiQueue', $this->service);
    }

    public function testAddQueue()
    {
        $this->assertInstanceOf('RoundPartner\Cloud\Queue\MultiQueue', $this->addQueue());
    }

    public function testAddMessage()
    {
        $this->addQueue();
        $this->assertTrue($this->service->addMessage('Hello world'));
    }

    public function testGetMessagesReturnsMessage()
    {
        $this->addQueue();
        $this->service->addMessage('Hello world');
        $result = $this->service->getMessages();
        $this->assertCount(1, $result);
    }

    public function testGetMessagesReturnsFromMultipleQueues()
    {
        foreach (range(1, 5) as $index) {
            $queue = new QueueMock();
            $queue->addMessage($index);
            $this->service->addQueue($queue);
        }
        $result = $this->service->getMessages();
        $this->assertCount(5, $result);
    }

    public function testGetMessageReturnsFromMultipleQueuesInLimit()
    {
        foreach (range(1, 5) as $index) {
            $queue = new QueueMock();
            $queue->addMessage($index);
            $this->service->addQueue($queue);
        }
        $result = $this->service->getMessages(2);
        $this->assertCount(2, $result);
    }


    /**
     * @return MultiQueue
     */
    private function addQueue()
    {
        return $this->service->addQueue(new QueueMock());
    }
}
