<?php

namespace RoundPartner\Test\Unit;

use OpenCloud\Tests\MockSubscriber;
use RoundPartner\Tests\CloudTestCase;
use RoundPartner\Cloud\Cloud;
use GuzzleHttp\Client;

class CloudTest extends CloudTestCase
{
    const TEST_QUEUE = 'tasks_dev';
    const AWS_TEST_QUEUE = 'aws:tasks_dev';

    /**
     * @var \RoundPartner\Cloud\Cloud
     */
    public $client;

    /**
     * @var \RoundPartner\Cloud\Message\Message[]
     */
    protected $messages;

    public function setUp()
    {
        $awsClient = new Client([
            'base_uri' => 'http://localhost:6767',
        ]);

        $client = $this->newClient();
        $client->addSubscriber(new MockSubscriber());
        $this->client = new Cloud($client, $awsClient, 'secret', 'DFW');

        $this->messages = array();
    }

    /**
     * @return \RoundPartner\Cloud\Queue
     */
    protected function getQueue()
    {
        return $this->client->queue(self::TEST_QUEUE, 'cloudQueues', 'DFW');
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
        $this->assertInstanceOf('\RoundPartner\Cloud\QueueInterface', $this->getQueue());
    }

    public function testQueueCreate()
    {
        $this->assertInstanceOf('\RoundPartner\Cloud\QueueInterface', $this->getQueue());
    }

    public function testAwsQueue()
    {
        $queue = $this->client->queue(self::AWS_TEST_QUEUE, 'cloudQueues', 'DFW');
        $this->assertInstanceOf('\RoundPartner\Cloud\QueueInterface', $queue);
    }

    public function testGetMessages()
    {
        $this->messages = $this->getQueue()->getMessages();
        $this->assertContainsOnlyInstancesOf('\RoundPartner\Cloud\Message\Message', $this->messages);
    }

    public function testInstanceOfDomain()
    {
        $this->assertInstanceOf('\RoundPartner\Cloud\Domain\Domain', $this->client->domain('cloudDNS', 'DFW'));
    }

    public function testInstanceOfDocument()
    {
        $this->assertInstanceOf('\RoundPartner\Cloud\Document\Document', $this->client->document('DFW'));
    }

    public function testMessageService()
    {
        $this->assertInstanceOf('\RoundPartner\Cloud\Message\MessageService', $this->client->message(self::TEST_QUEUE, 'cloudQueues', 'DFW'));
    }

    /**
     * @return \RoundPartner\Cloud\Queue
     */
    protected function getMultipleQueue()
    {
        return $this->client->queue(['alfred', 'betty', 'charlie'], 'cloudQueues', 'DFW');
    }

    public function testMultipleQueue()
    {
        $this->assertInstanceOf('\RoundPartner\Cloud\QueueInterface', $this->getMultipleQueue());
    }
}
