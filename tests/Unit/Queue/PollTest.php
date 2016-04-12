<?php

namespace RoundPartner\Unit\Queue;

use RoundPartner\Cloud\Queue\Poll;
use RoundPartner\Cloud\Queue\PollFactory;
use RoundPartner\Test\Mock\QueueMock;

class PollTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var QueueMock
     */
    protected $queue;

    /**
     * @var Poll
     */
    protected $poll;
    
    public function setUp()
    {
        $this->queue = new QueueMock();
        $this->poll = PollFactory::create($this->queue, 0, 0.1);
    }

    public function testNext()
    {
        $this->queue->addMessage('hello world');
        $this->assertEquals('hello world', $this->poll->next());
    }

    public function testCurrentIteration()
    {
        $this->queue->addMessage('hello world');
        $this->queue->addMessage('hello world');
        $this->queue->addMessage('hello world');
        while ($this->poll->next()) {

        }
        $this->assertEquals(4, $this->poll->currentIteration());
    }
}
