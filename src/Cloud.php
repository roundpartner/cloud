<?php

namespace RoundPartner\Cloud;

use RoundPartner\Cloud\Document\Document;
use RoundPartner\Cloud\Document\DocumentFactory;
use RoundPartner\Cloud\Domain\Domain;
use RoundPartner\Cloud\Domain\DomainFactory;
use RoundPartner\Cloud\Message\MessageService;
use RoundPartner\Cloud\Queue\QueueFactory;

class Cloud implements CloudInterface
{

    /**
     * @var \OpenCloud\Rackspace
     */
    protected $client;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var QueueInterface[]
     */
    protected $queueServices;

    /**
     * @var Domain
     */
    protected $domainService;

    /**
     * @var Document
     */
    protected $documentService;

    /**
     * Cloud constructor.
     *
     * @param Service\Cloud $client
     * @param string $secret
     */
    public function __construct($client, $secret)
    {
        $this->client = $client;
        $this->secret = $secret;
    }

    /**
     * @return \OpenCloud\Rackspace
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param string $queue
     * @param string $serviceName
     * @param string $region
     *
     * @return Queue
     */
    public function queue($queue, $serviceName = 'cloudQueues', $region = 'LON')
    {
        if (!isset($this->queueServices[$queue])) {
            $this->queueServices[$queue] = QueueFactory::create($this->client, $this->secret, $queue, $serviceName, $region);
        }
        return $this->queueServices[$queue];
    }

    /**
     * @param string $queue
     * @param string $serviceName
     * @param string $region
     *
     * @return MessageService
     */
    public function message($queue, $serviceName = 'cloudQueues', $region = 'LON')
    {
        $queue = $this->queue($queue, $serviceName, $region);
        return new MessageService($queue);
    }

    /**
     * @param string $serviceName
     * @param string $region
     *
     * @return Domain
     */
    public function domain($serviceName = 'cloudDNS', $region = 'LON')
    {
        if (null === $this->domainService) {
            $this->domainService = DomainFactory::create($this->client, $serviceName, $region);
        }
        return $this->domainService;
    }

    /**
     * @param string $region
     *
     * @return Document
     */
    public function document($region = 'LON')
    {
        if (null === $this->documentService) {
            $this->documentService = DocumentFactory::create($this->client, $region);
        }
        return $this->documentService;
    }
}
