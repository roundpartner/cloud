<?php

namespace RoundPartner\Test\Unit;

class CloudTest extends \PHPUnit_Framework_TestCase
{
    const TEST_QUEUE = 'tasks_dev';
    const TEST_NEW_QUEUE = 'test_queue';

    /**
     * @var \RoundPartner\Cloud\Cloud
     */
    protected $client;

    /**
     * @var \RoundPartner\Cloud\Message\Message[]
     */
    protected $messages;

    public function setUp()
    {
        $config = \RoundPartner\Conf\Service::get('opencloud');
        $this->client = \RoundPartner\Cloud\CloudFactory::create($config['username'], $config['key'], $config['secret']);
        $this->messages = array();
    }

    /**
     * Clean up
     */
    public function tearDown()
    {
        foreach ($this->messages as $message) {
            $message->delete();
        }
    }

    public function testGetClient()
    {
        $this->assertInstanceOf('\OpenCloud\Rackspace', $this->client->getClient());
    }

    public function testQueue()
    {
        $this->assertInstanceOf('\RoundPartner\Cloud\QueueInterface', $this->client->queue(self::TEST_QUEUE));
    }

    public function testDeleteQueue()
    {
        $this->assertTrue($this->client->queue(self::TEST_NEW_QUEUE)->delete());
    }

    public function testQueueCreate()
    {
        $this->assertInstanceOf('\RoundPartner\Cloud\QueueInterface', $this->client->queue(self::TEST_NEW_QUEUE));
    }

    public function testAddMessage()
    {
        $this->client->queue(self::TEST_QUEUE)->addMessage(new \RoundPartner\Cloud\Task\Entity\Task());
    }

    public function testGetMessages()
    {
        $this->messages = $this->client->queue(self::TEST_QUEUE)->getMessages();
        $this->assertContainsOnlyInstancesOf('\RoundPartner\Cloud\Message\Message', $this->messages);
    }

    public function testGetMessageIsTask()
    {
        $this->client->queue(self::TEST_QUEUE)->addMessage(new \RoundPartner\Cloud\Task\Entity\Task());
        $this->messages = $this->client->queue(self::TEST_QUEUE)->getMessages();
        $this->assertInstanceOf('\RoundPartner\Cloud\Task\Entity\Task', $this->messages[0]->getBody());
    }

    public function testGetMessagesMultiple()
    {
        $this->client->queue(self::TEST_QUEUE)->addMessage(new \RoundPartner\Cloud\Task\Entity\Task());
        $this->client->queue(self::TEST_QUEUE)->getMessages(100);
        $messages = $this->client->queue(self::TEST_QUEUE)->getMessages();
        $this->assertEmpty($messages);
    }

    public function testInstanceOfDomain()
    {
        $this->assertInstanceOf('\RoundPartner\Cloud\Domain\Domain', $this->client->domain());
    }

    public function testInstanceOfDocument()
    {
        $this->assertInstanceOf('\RoundPartner\Cloud\Document\Document', $this->client->document());
    }
}
