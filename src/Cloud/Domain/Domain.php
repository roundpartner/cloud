<?php

namespace RoundPartner\Cloud\Domain;

use OpenCloud\DNS\Service;
use OpenCloud\Rackspace;

class Domain
{
    /**
     * @var Rackspace
     */
    protected $client;

    /**
     * @var Service
     */
    protected $service;

    /**
     * Domain constructor.
     *
     * @param Rackspace $client
     * @param string $serviceName
     * @param string $region
     */
    public function __construct($client, $serviceName = 'cloudDNS', $region = 'LON')
    {
        $this->client = $client;
        $this->service = $client->dnsService($serviceName, $region);
    }

    /**
     * @param string $domain
     *
     * @return \OpenCloud\DNS\Resource\Domain
     * @throws \OpenCloud\Common\Exceptions\DomainNotFoundException
     */
    public function getDomain($domain)
    {
        return $this->service->domainByName($domain);
    }
}
