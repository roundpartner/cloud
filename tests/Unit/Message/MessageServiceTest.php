<?php

namespace RoundPartner\Unit;

use RoundPartner\Cloud\Message\MessageService;
use RoundPartner\Cloud\Queue;

class MessageServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MessageService
     */
    protected $service;

    /**
     * @var \RoundPartner\Cloud\Cloud
     */
    protected $client;

    public function setUp()
    {
        $config = \RoundPartner\Conf\Service::get('opencloud');
        $this->client = \RoundPartner\Cloud\CloudFactory::create($config['username'], $config['key'], $config['secret']);
        $queue = $this->client->queue('test_message_queue');
        $this->service = new MessageService($queue);
    }

    public function testNewInstance()
    {
        $this->assertInstanceOf('\RoundPartner\Cloud\Message\MessageService', $this->service);
    }

    public function testPost()
    {
        $this->assertTrue($this->service->post($this->getObject()));
    }

    public function testPostFailsOnInvalidObject()
    {
        $this->assertFalse($this->service->post($this->getInvalidObject()));
    }

    public function testGetReturnsArray()
    {
        $this->assertInternalType('array', $this->service->get());
    }

    public function testGetReturnsSingleMessage()
    {
        $this->assertTrue($this->service->post($this->getObject()));
        $this->assertCount(1, $this->service->get());
    }

    public function testGetReturnsMultipleMessages()
    {
        foreach (range(1, 5) as $total) {
            $this->assertTrue($this->service->post($this->getObject()));
        }
        $this->assertCount($total, $this->service->get());
    }

    private function getObject()
    {
        $object = new \stdClass();
        $object->hash = base64_encode(openssl_random_pseudo_bytes(64));
        return $object;
    }

    private function getInvalidObject()
    {
        $object = new \stdClass();
        $object->fail = function () {
        };
        return $object;
    }
}
