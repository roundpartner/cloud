<?php

namespace RoundPartner\Unit\Queue;

use RoundPartner\Cloud\Queue\Poll;
use RoundPartner\Cloud\Queue\PollFactory;
use RoundPartner\Tests\Mock\QueueMock;

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

    public function testPollExpires()
    {
        $this->queue->addMessage('hello world');
        sleep(1);
        $this->assertNull($this->poll->next());
    }

    public function testHasNext()
    {
        $this->queue->addMessage('hello world');
        $this->assertEquals(true, $this->poll->hasNext());
    }

    public function testHasNextWhenDisabled()
    {
        $this->queue->addMessage('hello world');
        $this->assertEquals(false, $this->poll->hasNext(true));
    }

    public function testHasNextWhenDisabledAfterPoll()
    {
        $this->queue->addMessage('hello world');
        $this->poll->hasNext();
        $this->assertEquals(true, $this->poll->hasNext(true));
    }

    public function testHasNextIsFalseWhenEmpty()
    {
        $this->queue->addMessage('hello world');
        $this->poll->next();
        $this->assertEquals(false, $this->poll->hasNext());
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

    public function testGetTimeRemaining()
    {
        $this->poll = PollFactory::create($this->queue, PollFactory::MINUTE);
        sleep(1);
        $this->assertEquals(PollFactory::MINUTE - 1, $this->poll->getTimeRemaining());
    }

    public function testGetFiftyTasks()
    {
        foreach (range(1, 50, 1) as $iteration) {
            $this->queue->addMessage('hello world ' . $iteration);
        }
        foreach (range(1, 50, 1) as $iteration) {
            $this->assertEquals('hello world ' . $iteration, $this->poll->next());
        }
    }

    public function testGetAllTasksAfterTimeout()
    {
        $this->queue->addMessage('hello world');
        $this->poll->hasNext();
        sleep(1);
        $this->assertEquals('hello world', $this->poll->next());
    }
}
