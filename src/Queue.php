<?php

namespace RoundPartner\Cloud;

use OpenCloud\Queues\Resource\Claim;
use OpenCloud\Queues\Resource\Message;
use RoundPartner\Cloud\Queue\Entity\Stats;
use RoundPartner\VerifyHash\VerifyHash;

class Queue implements QueueInterface
{

    /**
     * @var \OpenCloud\Queues\Resource\Queue
     */
    protected $service;

    /**
     * @var string
     */
    protected $secret;

    /**
     * Queue constructor.
     *
     * @param \OpenCloud\Queues\Resource\Queue $queue
     * @param string $secret
     */
    public function __construct(\OpenCloud\Queues\Resource\Queue $queue, $secret)
    {
        $this->service = $queue;
        $this->secret = $secret;
    }

    /**
     * @param mixed $message
     * @param int $ttl
     *
     * @return bool
     */
    public function addMessage($message, $ttl = 600)
    {
        $verifyHash = new VerifyHash($this->secret);
        $messageString = serialize($message);
        $object = array(
            'body' => array(
                'serial' => $messageString,
                'sha1' => $verifyHash->hash($messageString),
            ),
            'ttl' => $ttl
        );
        return $this->service->createMessage($object);
    }

    /**
     * @param int $limit
     * @param int $grace
     * @param int $ttl
     *
     * @return Message\Message[]
     *
     * @throws \Exception
     */
    public function getMessages($limit = Claim::LIMIT_DEFAULT, $grace = CLAIM::GRACE_DEFAULT, $ttl = CLAIM::TTL_DEFAULT)
    {
        $messages = $this->claimMessages(array(
            'limit' => $limit,
            'grace' => $grace,
            'ttl'   => $ttl
        ));

        return $this->processMessages($messages);
    }

    /**
     * @param array $options
     *
     * @return Message[]
     */
    private function claimMessages(array $options = array())
    {
        return $this->service->claimMessages($options);
    }

    /**
     * @param \OpenCloud\Queues\Resource\Message[] $messages
     *
     * @return Message\Message[]
     *
     * @throws \Exception
     */
    private function processMessages($messages)
    {
        $response = array();

        if ($messages === false) {
            return $response;
        }

        foreach ($messages as $message) {
            $response[] = new \RoundPartner\Cloud\Message\Message($message, $this->secret);
        }
        return $response;
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $response = $this->service->delete();
        return 204 === $response->getStatusCode();
    }

    /**
     * @return Stats
     */
    public function getStats()
    {
        $stats = $this->service->getStats();
        return Stats::factory($stats);
    }
}
