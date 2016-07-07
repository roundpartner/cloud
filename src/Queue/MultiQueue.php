<?php

namespace RoundPartner\Cloud\Queue;

use OpenCloud\Queues\Resource\Claim;
use RoundPartner\Cloud\Message;
use RoundPartner\Cloud\QueueInterface;

class MultiQueue implements QueueInterface
{

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var QueueInterface[]
     */
    protected $queues;

    /**
     * MultiQueue constructor.
     *
     * @param $secret
     */
    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @param QueueInterface $queue
     *
     * @return MultiQueue
     */
    public function addQueue($queue)
    {
        $this->queues[] = $queue;
        return $this;
    }

    /**
     * @param Message $message
     * @param int $ttl
     *
     * @return bool
     */
    public function addMessage($message, $ttl = 600)
    {
        return $this->queues[0]->addMessage($message, $ttl);
    }

    /**
     * @param int $limit
     * @param int $grace
     * @param int ttl
     *
     * @return Message[]
     *
     * @throws \Exception
     */
    public function getMessages($limit = Claim::LIMIT_DEFAULT, $grace = CLAIM::GRACE_DEFAULT, $ttl = CLAIM::TTL_DEFAULT)
    {
        $originalLimit = $limit;
        $messages = array();
        foreach ($this->queues as $queue) {
            $limit = $originalLimit - count($messages);
            if ($limit < 0) {
                return $messages;
            }
            $messages = array_merge($messages, $queue->getMessages($limit, $grace, $ttl));
        }
        return $messages;
    }
}
