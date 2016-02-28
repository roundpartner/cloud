<?php

namespace Cloud;

use VerifyHash\VerifyHash;

class Cloud
{

    /**
     * @var \OpenCloud\Rackspace
     */
    protected $client;

    protected $secret;

    public function __construct($username, $apiKey, $secret)
    {
        $this->client = new \OpenCloud\Rackspace(\OpenCloud\Rackspace::UK_IDENTITY_ENDPOINT, array(
            'username' => $username,
            'apiKey' => $apiKey,
        ));
        $this->secret = $secret;
    }

    public function addMessage($queue, $message)
    {
        $verifyHash = new VerifyHash($this->secret);
        $messageString = serialize($message);
        $object = array(
            'body' => array(
                'serial' => $messageString,
                'sha1' => $verifyHash->hash($messageString),
            ),
            'ttl' => 500,
        );
        $queueService = $this->getQueue($queue);
        return $queueService->createMessage($object);
    }

    public function getMessages($queue)
    {
        $queueService = $this->getQueue($queue);
        $messages = $queueService->claimMessages(array(
            'limit' => 10,
            'grace' => 60,
            'ttl'   => 500
        ));
        $response = array();
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
        }
        return $response;
    }

    public function getQueue($queue, $serviceName='cloudQueues', $region='LON')
    {
        $service = $this->client->queuesService($serviceName, $region);
        $service->setClientId();
        return $service->getQueue($queue);
    }


}