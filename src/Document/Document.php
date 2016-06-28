<?php

namespace RoundPartner\Cloud\Document;

use Guzzle\Http\Exception\BadResponseException;
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

    /**
     * @param string $name
     *
     * @return bool
     */
    public function containerExists($name)
    {
        return $this->getContainer($name) !== false;
    }

    /**
     * @param string $name
     *
     * @return \OpenCloud\ObjectStore\Resource\Container
     */
    public function getContainer($name)
    {
        try {
            return $this->service->getContainer($name);
        } catch (BadResponseException $exception) {
            if ($exception->getRequest()->getResponse()->getStatusCode() === 404) {
                return false;
            }
            throw $exception;
        }
    }
}
