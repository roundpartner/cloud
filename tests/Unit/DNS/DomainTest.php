<?php

namespace RoundPartner\Test\Unit\Domain;

use RoundPartner\Cloud\Domain\DomainFactory;
use OpenCloud\Tests\MockSubscriber;
use RoundPartner\Tests\CloudTestCase;
use RoundPartner\Cloud\Domain\Domain;

class DomainTest extends CloudTestCase
{
    /**
     * @var Domain
     */
    public $service;

    /**
     * @var \RoundPartner\Cloud\Cloud
     */
    public $client;

    /**
     * @var array
     */
    protected $domainConfig;

    public function setUp()
    {
        $this->client = $this->newClient();
        $this->client->addSubscriber(new MockSubscriber());
        $this->service = DomainFactory::create($this->client);
        $this->domainConfig = array(
            'domain' => 'test',
            'home' => 'test.domain.com',
            'ip' => '127.0.0.1',
        );
    }

    /**
     * @param string $body
     * @param int $status
     *
     * @dataProvider \RoundPartner\Tests\Providers\QueueProvider::domain()
     */
    public function testGetDomain($body, $status)
    {
        $this->addMockSubscriber($this->makeResponse($body, $status));
        $this->assertInstanceOf('\OpenCloud\DNS\Resource\Domain', $this->service->getDomain($this->domainConfig['domain']));
    }

    /**
     * @param string $body
     * @param int $status
     *
     * @dataProvider \RoundPartner\Tests\Providers\QueueProvider::domain()
     */
    public function testUpdateSubDomain($body, $status)
    {
        $this->addMockSubscriber($this->makeResponse($body, $status));
        $this->addMockSubscriber($this->makeResponse($body, $status));
        $this->addMockSubscriber($this->makeResponse(null, 201));
        $domain = $this->service->getDomain($this->domainConfig['domain']);
        $result = $this->service->updateSubDomain($domain, $this->domainConfig['home'], $this->domainConfig['ip']);
        $this->assertTrue($result);
    }
}
