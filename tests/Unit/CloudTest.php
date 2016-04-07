<?php

class CloudTest extends PHPUnit_Framework_TestCase
{
    const TEST_QUEUE = 'tasks_dev';

    /**
     * @var \RoundPartner\Cloud\Cloud
     */
    protected $client;

    public function setUp()
    {
        $config = \RoundPartner\Conf\Service::get('opencloud');
        $this->client = \RoundPartner\Cloud\CloudFactory::create($config['username'], $config['key'], $config['secret']);
    }

    public function testQueue()
    {
        $this->assertInstanceOf('\RoundPartner\Cloud\QueueInterface', $this->client->queue(self::TEST_QUEUE));
    }

    public function testAddMessage()
    {
        $this->client->queue(self::TEST_QUEUE)->addMessage(new \RoundPartner\Cloud\Entity\Task());
    }

    public function testGetMessages()
    {
        $messages = $this->client->queue(self::TEST_QUEUE)->getMessages();
        $this->assertContainsOnlyInstancesOf('\RoundPartner\Cloud\Entity\Task', $messages);
    }

    public function testGetMessagesMultiple()
    {
        $this->client->addMessage(self::TEST_QUEUE, new \RoundPartner\Cloud\Entity\Task());
        $this->client->getMessages(self::TEST_QUEUE, 100);
        $messages = $this->client->getMessages(self::TEST_QUEUE);
        $this->assertEmpty($messages);
    }

}