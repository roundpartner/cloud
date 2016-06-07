<?php

namespace RoundPartner\Cloud;

use RoundPartner\Cloud\Domain\Domain;
use RoundPartner\Cloud\Domain\DomainFactory;
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
     * @return Domain\Domain
     */
    public function domain()
    {
        if ($this->domainService === null) {
            $this->domainService = DomainFactory::create($this->client);
        }
        return $this->domainService;
    }
}