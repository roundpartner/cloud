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

    public function setUp()
    {
        $config = Service::get('opencloud');
        $client = new Cloud($config['username'], $config['key']);
        $this->service = DomainFactory::create($client);
    }

    public function testListDomains()
    {
        $this->assertNotEmpty($this->service->listDomains());
    }
}
