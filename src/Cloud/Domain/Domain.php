<?php

namespace RoundPartner\Cloud\Domain;

use OpenCloud\DNS\Resource\Record;
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

    /**
     * @param \OpenCloud\DNS\Resource\Domain $domain
     * @param string $name
     * @param string $data
     *
     * @return bool
     */
    public function updateSubDomain($domain, $name, $data)
    {
        $records = $domain->recordList(['type' => 'A']);
        if (count($records) === 0) {
            return false;
        }
        foreach ($records as $record) {
            if ($record->name === $name) {
                $this->updateRecord($record, $data);
            }
        }
        return true;
    }

    /**
     * @param Record $record
     * @param string $data
     *
     * @return \Guzzle\Http\Message\Response|\OpenCloud\DNS\Resource\AsyncResponse
     */
    private function updateRecord(Record $record, $data)
    {
        return $record->update(array(
            'data' => $data
        ));
    }
}
