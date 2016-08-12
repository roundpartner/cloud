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
    public function __construct(Service\Cloud $client, $secret)
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
     *
     * @return Queue
     */
    public function queue($queue)
    {
        if (!isset($this->queueServices[$queue])) {
            $this->queueServices[$queue] = QueueFactory::create($this->client, $this->secret, $queue);
        }
        return $this->queueServices[$queue];
    }

    /**
     * @param $queue
     *
     * @return MessageService
     */
    public function message($queue)
    {
        $queue = $this->queue($queue);
        return new MessageService($queue);
    }

    /**
     * @return Domain
     */
    public function domain()
    {
        if (null === $this->domainService) {
            $this->domainService = DomainFactory::create($this->client);
        }
        return $this->domainService;
    }

    /**
     * @return Document
     */
    public function document()
    {
        if (null === $this->documentService) {
            $this->documentService = DocumentFactory::create($this->client);
        }
        return $this->documentService;
    }
}
