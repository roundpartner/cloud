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
        $this->setUpMultiQueue();
        $result = $this->service->getMessages(5);
        $this->assertCount(5, $result);
    }

    public function testGetMessageReturnsFromMultipleQueuesInLimit()
    {
        $this->setUpMultiQueue();
        $result = $this->service->getMessages(2);
        $this->assertCount(2, $result);
    }

    public function testGetManyMessagesFromMultiQueues()
    {
        $queue = new QueueMock();
        foreach (range(1, 5, 1) as $queueIteration) {
            foreach (range(1, 25, 1) as $iteration) {
                $queue->addMessage('hello world ' . $iteration . ' ' . $queueIteration);
            }
            $this->service->addQueue($queue);
        }
        $this->service->getMessages(80);
        $result = $this->service->getMessages(80);
        $this->assertCount(45, $result);
    }

    /**
     * @return MultiQueue
     */
    private function addQueue()
    {
        return $this->service->addQueue(new QueueMock());
    }

    private function setUpMultiQueue()
    {
        foreach (range(1, 15) as $index) {
            $queue = new QueueMock();
            $queue->addMessage($index);
            $this->service->addQueue($queue);
        }
    }
}
