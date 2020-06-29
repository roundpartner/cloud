<?php

namespace RoundPartner\Unit\Queue;

use RoundPartner\Cloud\Queue\Entity\SqsMessage;
use RoundPartner\Cloud\Task\TaskFactory;
use RoundPartner\Tests\CloudTestCase;

class SqsMessageTest extends CloudTestCase
{
    public function testCreateMessage()
    {
        $task = TaskFactory::create('test', 'test', 'test', []);
        $task->next = TaskFactory::create('test 2', 'test2', 'test2', []);
        $json = json_encode($task);
        $sqsMessage = new SqsMessage($json);
        $this->assertEquals('test', $sqsMessage->task->taskName);
    }

    public function testCreateChainedMessage()
    {
        $task = TaskFactory::create('test', 'test', 'test', []);
        $task->next = TaskFactory::create('test two', 'test2', 'test2', []);
        $json = json_encode($task);
        $sqsMessage = new SqsMessage($json);
        $this->assertEquals('test two', $sqsMessage->task->next->taskName);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Json Decode Error: "Syntax error" from json: "{invalid"
     */
    public function testCreateInvalidMessage()
    {
        $json = '{"Message":"{invalid"}';
        $sqsMessage = new SqsMessage($json);
    }
}