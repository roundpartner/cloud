<?php

namespace RoundPartner\Cloud;

use OpenCloud\Queues\Resource\Message;
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
     * @param integer $limit
     *
     * @return mixed[]
     *
     * @throws \Exception
     */
    public function getMessages($limit = 10)
    {
        $messages = $this->service->claimMessages(array(
            'limit' => $limit,
            'grace' => 60,
            'ttl'   => 500
        ));

        $response = $this->processMessages($messages);
        return $response;
    }

    /**
     * @param Message[] $messages
     *
     * @return array
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
            $body = $message->getBody();
            if (isset($body->serial)) {
                $verifyHash = new VerifyHash($this->secret);
                if ($verifyHash->verify($body->sha1, $body->serial)) {
                    $response[] = unserialize($body->serial);
                } else {
                    throw new \Exception('secret could not be verified');
                }
            }
            $message->delete($message->getClaimIdFromHref());
        }
        return $response;
    }
}
