<?php

namespace RoundPartner\Unit\Document;

use RoundPartner\Cloud\Document\DocumentFactory;
use RoundPartner\Cloud\Service\Cloud;
use RoundPartner\Cloud\Document\Document;
use RoundPartner\Conf\Service;

class DocumentFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var Document
     */
    protected $service;

    public function setUp()
    {
        $config = Service::get('opencloud');
        $client = new Cloud($config['username'], $config['key']);
        $this->config = Service::get('testclouddocument');
        $this->service = DocumentFactory::create($client);
    }

    public function testCreateInstance()
    {
        $this->assertInstanceOf('RoundPartner\Cloud\Document\Document', $this->service);
    }
}
