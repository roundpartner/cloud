<?php

namespace RoundPartner\Cloud;

use OpenCloud\Queues\Resource\Claim;
use OpenCloud\Queues\Resource\Message;
use RoundPartner\Cloud\Queue\Entity\MessageStats;
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
        $messageString = $this->serialiseMessage($message);
        if ($messageString === false) {
            return false;
        }
        $object = $this->createMessageObject($ttl, $messageString);
        return $this->service->createMessage($object);
    }

    /**
     * @param int $limit
     * @param int $grace Seconds to extend life time of task when it is put back into the queue
     * @param int $ttl Seconds to hold onto the task when taken from the queue
     *
     * @return Message\Message[]
     *
     * @see https://developer.rackspace.com/docs/cloud-queues/v1/api-reference/claims-operations/#claim-messages
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
     * @param MessageStats $messageStat
     *
     * @return \RoundPartner\Cloud\Message\Message
     */
    public function getMessage($messageStat)
    {
        $hrefParts = explode('/', $messageStat->href);
        $href = array_pop($hrefParts);
        $message = $this->service->getMessage($href);
        return new \RoundPartner\Cloud\Message\Message($message, $this->secret);
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

    /**
     * @param $message
     * @return bool|string
     */
    private function serialiseMessage($message)
    {
        try {
            $messageString = serialize($message);
            return $messageString;
        } catch (\Exception $exception) {
            $messageString = false;
            return $messageString;
        }
        return $messageString;
    }

    /**
     * @param $ttl
     * @param $messageString
     * @return array
     */
    private function createMessageObject($ttl, $messageString)
    {
        $verifyHash = new VerifyHash($this->secret);
        $object = array(
            'body' => array(
                'serial' => $messageString,
                'sha1' => $verifyHash->hash($messageString),
            ),
            'ttl' => $ttl
        );
        return $object;
    }
}
