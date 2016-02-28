<?php

class CloudTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \Cloud\Cloud
     */
    protected $client;

    public function setUp()
    {
        $config = require dirname(__DIR__) . '/../vendor/rp/conf/auth.php';
        $this->client = new \Cloud\Cloud($config['opencloud']['username'], $config['opencloud']['key'], $config['opencloud']['secret']);
    }

    public function testCreateClass()
    {
        $this->assertInstanceOf('\Cloud\Cloud', $this->client);
    }

    public function testGetQueue()
    {
        $this->assertInstanceOf('OpenCloud\Queues\Resource\Queue', $this->client->getQueue('tasks_dev'));
    }

    public function testAddMessage()
    {
        $this->client->addMessage('tasks_dev', new \Cloud\Entity\Task());
    }

    public function testGetMessages()
    {
        $messages = $this->client->getMessages('tasks_dev');
        foreach($messages as $message) {
            $body = $message;
            var_dump($body);
        }
    }

}