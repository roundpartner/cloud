<?php

namespace RoundPartner\Cloud\Queue;

use RoundPartner\Cloud\QueueInterface;
use OpenCloud\Queues\Resource\Claim;

class Seq implements QueueInterface
{
    /**
     * @var \RoundPartner\Seq\Seq
     */
    protected $service;

    /**
     * Seq constructor.
     *
     * @param \RoundPartner\Seq\Seq $client
     */
    public function __construct($client)
    {
        $this->service = $client;
    }

    /**
     * @param \RoundPartner\Cloud\Message\Message $message
     * @param int $ttl
     *
     * @return bool
     */
    public function addMessage($message, $ttl = 600)
    {
        $this->service->post($message);
        return false;
    }

    /**
     * @param int $limit
     * @param int $grace
     * @param int ttl
     *
     * @return \RoundPartner\Cloud\Message\Message[]
     *
     * @throws \Exception
     */
    public function getMessages($limit = Claim::LIMIT_DEFAULT, $grace = CLAIM::GRACE_DEFAULT, $ttl = CLAIM::TTL_DEFAULT)
    {
        $message = $this->service->get();
        if (null === $message) {
            return [];
        }
        return [$message];
    }
}