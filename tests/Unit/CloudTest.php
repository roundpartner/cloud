<?php

class CloudTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \RoundPartner\Cloud\Cloud
     */
    protected $client;

    public function setUp()
    {
        $config = \RoundPartner\Conf\Service::get('opencloud');
        $this->client = \RoundPartner\Cloud\CloudFactory::create($config['username'], $config['key'], $config['secret']);
    }

    public function testAddMessage()
    {
        $this->client->addMessage('tasks_dev', new \RoundPartner\Cloud\Entity\Task());
    }

    public function testGetMessages()
    {
        $messages = $this->client->getMessages('tasks_dev');
        $this->assertContainsOnlyInstancesOf('\RoundPartner\Cloud\Entity\Task', $messages);
    }

    public function testGetMessagesMultiple()
    {
        $this->client->addMessage('tasks_dev', new \RoundPartner\Cloud\Entity\Task());
        $this->client->getMessages('tasks_dev', 100);
        $messages = $this->client->getMessages('tasks_dev');
        $this->assertEmpty($messages);
    }

}