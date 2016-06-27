<?php

namespace RoundPartner\Test\Unit\Domain;

use RoundPartner\Cloud\Domain\DomainFactory;
use RoundPartner\Cloud\Service\Cloud;
use RoundPartner\Conf\Service;
use RoundPartner\Cloud\Domain\Domain;

class DomainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Domain
     */
    protected $service;

    /**
     * @var array
     */
    protected $domainConfig;

    public function setUp()
    {
        $config = Service::get('opencloud');
        $client = new Cloud($config['username'], $config['key']);
        $this->service = DomainFactory::create($client);
        $this->domainConfig = Service::get('testdomain');
    }

    public function testGetDomain()
    {
        $this->assertInstanceOf('\OpenCloud\DNS\Resource\Domain', $this->service->getDomain($this->domainConfig['domain']));
    }

    public function testUpdateSubDomain()
    {
        $domain = $this->service->getDomain($this->domainConfig['domain']);
        $result = $this->service->updateSubDomain($domain, $this->domainConfig['home'], $this->domainConfig['ip']);
        $this->assertTrue($result);
    }
}
