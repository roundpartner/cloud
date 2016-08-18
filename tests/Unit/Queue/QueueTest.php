<?php

namespace RoundPartner\Unit\Queue;

class QueueTest extends \PHPUnit_Framework_TestCase
{
    const TEST_QUEUE = 'tasks_dev';
    const TEST_NEW_QUEUE = 'test_queue';

    /**
     * @var \RoundPartner\Cloud\Cloud
     */
    protected $client;

    public function setUp()
    {
        $config = \RoundPartner\Conf\Service::get('opencloud');
        $this->client = \RoundPartner\Cloud\CloudFactory::create($config['username'], $config['key'], $config['secret']);
    }

    public function testGetStats()
    {
        $this->assertObjectHasAttribute('total', $this->client->queue(self::TEST_QUEUE)->getStats());
    }

    public function testDeleteQueue()
    {
        $this->assertTrue($this->client->queue(self::TEST_NEW_QUEUE)->delete());
    }
}
