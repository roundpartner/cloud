<?php

namespace RoundPartner\Cloud;

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
     * @return Message[]
     *
     * @throws \Exception
     */
    public function getMessages($limit = 10, $grace = 60, $ttl = 600)
    {
        $messages = $this->service->claimMessages(array(
            'limit' => $limit,
            'grace' => $grace,
            'ttl'   => $ttl
        ));

        return $this->processMessages($messages);
    }

    /**
     * @param \OpenCloud\Queues\Resource\Message[] $messages
     *
     * @return Message[]
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
            $response[] = new Message($message, $this->secret);
        }
        return $response;
    }
}
