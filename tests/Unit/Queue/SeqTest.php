<?php

namespace RoundPartner\Unit\Queue;

use RoundPartner\Cloud\Queue\Seq;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

use RoundPartner\Seq\Seq as SeqClient;

class SeqTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \RoundPartner\Seq\Seq
     */
    public $client;

    /**
     * @var Seq
     */
    protected $service;

    public function setUp()
    {
        $this->client = new SeqClient();
        $this->service = new Seq($this->client);
    }

    public function testInstanceOfSeq()
    {
        $this->assertInstanceOf('\RoundPartner\Cloud\Queue\Seq', $this->service);
    }

    /**
     * @param Response[] $responses
     *
     * @dataProvider \Test\Provider\ResponseProvider::getEmpty()
     */
    public function testGetNoMessage($responses)
    {
        $client = $this->getClientMock($responses);
        $this->client->setClient($client);
        $response = $this->service->getMessages();
        $this->assertEmpty($response);
    }

    /**
     * @param Response[] $responses
     *
     * @dataProvider \Test\Provider\ResponseProvider::get()
     */
    public function testGetSingleMessage($responses)
    {
        $client = $this->getClientMock($responses);
        $this->client->setClient($client);
        $response = $this->service->getMessages();
        $this->assertCount(1, $response);
    }

    /**
     * @param Response[] $responses
     *
     * @dataProvider \Test\Provider\ResponseProvider::post()
     */
    public function testAddMessage($responses)
    {
        $client = $this->getClientMock($responses);
        $this->client->setClient($client);
        $task = new \RoundPartner\Cloud\Task\Entity\Task();
        $task->taskName = 'hello world';
        $this->assertTrue($this->service->addMessage($task));
    }

    /**
     * @param Response[] $responses
     *
     * @return Client
     */
    protected function getClientMock($responses)
    {
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        return $client;
    }
}