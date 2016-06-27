<?php

namespace RoundPartner\Cloud\Document;

use OpenCloud\Rackspace;

class Document
{
    /**
     * @var Rackspace
     */
    protected $client;

    /**
     * @var \OpenCloud\ObjectStore\Service
     */
    protected $service;

    /**
     * Document constructor.
     *
     * @param Rackspace $client
     * @param string $serviceName
     * @param string $region
     */
    public function __construct($client, $serviceName = 'cloudFiles', $region = 'LON')
    {
        $this->client = $client;
        $this->service = $this->client->objectStoreService($serviceName, $region);
    }
}
