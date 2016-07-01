<?php

namespace RoundPartner\Cloud\Document;

use Guzzle\Http\Exception\BadResponseException;
use OpenCloud\ObjectStore\Resource\DataObject;
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
     * @param string $region
     */
    public function __construct($client, $region = 'LON')
    {
        $this->client = $client;
        $this->service = $this->client->objectStoreService('cloudFiles', $region);
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
            return $this->returnFalseOnObjectNotFoundExceptions($exception);
        }
    }

    /**
     * @param string $containerName
     * @param string $name
     * @param string $body
     *
     * @return DataObject
     */
    public function postDocument($containerName, $name, $body)
    {
        $container = $this->getContainer($containerName);
        if ($container === false) {
            return false;
        }
        try {
            return $container->uploadObject($name, $body);
        } catch (BadResponseException $exception) {
            return $this->returnFalseOnObjectNotFoundExceptions($exception);
        }
    }

    /**
     * @param string $containerName
     * @param string $name
     *
     * @return DataObject
     */
    public function getDocument($containerName, $name)
    {
        $container = $this->getContainer($containerName);
        if ($container === false) {
            return false;
        }
        try {
            return $container->getObject($name);
        } catch (ObjectNotFoundException $exception) {
            return false;
        }
    }

    /**
     * @param BadResponseException $exception
     *
     * @return bool
     */
    private function returnFalseOnObjectNotFoundExceptions(BadResponseException $exception)
    {
        $responseCode = $exception->getRequest()->getResponse()->getStatusCode();
        if (404 === $responseCode) {
            return false;
        }
        throw $exception;
    }
}
