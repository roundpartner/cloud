<?php

namespace RoundPartner\Cloud;

use RoundPartner\Cloud\Document\Document;
use RoundPartner\Cloud\Document\DocumentFactory;
use RoundPartner\Cloud\Domain\Domain;
use RoundPartner\Cloud\Domain\DomainFactory;
use RoundPartner\Cloud\Message\MessageService;
use RoundPartner\Cloud\Queue\AwsQueueFactory;
use RoundPartner\Cloud\Queue\MultiQueue;
use RoundPartner\Cloud\Queue\QueueFactory;

class Cloud implements CloudInterface
{

    /**
     * @var \OpenCloud\Rackspace
     */
    protected $client;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $awsClient;

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
     * @param \GuzzleHttp\Client $awsClient
     * @param string $secret
     */
    public function __construct($client, \GuzzleHttp\Client $awsClient, $secret)
    {
        $this->client = $client;
        $this->awsClient = $awsClient;
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
     * @return QueueInterface
     */
    public function queue($queue, $serviceName = 'cloudQueues', $region = 'LON')
    {
        if (is_array($queue)) {
            return $this->queueMultiple($queue, $serviceName, $region);
        }
        if (!isset($this->queueServices[$queue])) {
            if (strpos($queue, 'aws:') === 0) {
                $this->queueServices[$queue] = AwsQueueFactory::create($this->awsClient, $this->secret, $queue, $serviceName, $region);
            } else {
                $this->queueServices[$queue] = QueueFactory::create($this->client, $this->secret, $queue, $serviceName, $region);
            }
        }
        return $this->queueServices[$queue];
    }

    /**
     * @param array $queues
     * @param string $serviceName
     * @param string $region
     *
     * @return QueueInterface
     */
    private function queueMultiple($queues, $serviceName = 'cloudQueues', $region = 'LON')
    {
        $multiQueue = new MultiQueue();
        foreach ($queues as $queue) {
            $multiQueue->addQueue($this->queue($queue, $serviceName, $region));
        }
        return $multiQueue;
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
