<?php

namespace Cloud;

use RoundPartner\VerifyHash\VerifyHash;

class Cloud
implements CloudInterface
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
     * Cloud constructor.
     *
     * @param string $username
     * @param string $apiKey
     * @param string $secret
     */
    public function __construct($username, $apiKey, $secret)
    {
        $this->client = new \OpenCloud\Rackspace(\OpenCloud\Rackspace::UK_IDENTITY_ENDPOINT, array(
            'username' => $username,
            'apiKey' => $apiKey,
        ));
        $this->secret = $secret;
    }

    /**
     * @param string $queue
     * @param mixed $message
     * @return bool
     */
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

    /**
     * @param string $queue
     * @param integer $limit
     *
     * @return mixed[]
     * @throws \Exception
     */
    public function getMessages($queue, $limit = 10)
    {
        $queueService = $this->getQueue($queue);
        $messages = $queueService->claimMessages(array(
            'limit' => $limit,
            'grace' => 60,
            'ttl'   => 500
        ));

        $response = $this->processMessages($messages);
        return $response;
    }

    /**
     * @param string $queue
     * @param string $serviceName
     * @param string $region
     * @return \OpenCloud\Queues\Resource\Queue
     */
    public function getQueue($queue, $serviceName='cloudQueues', $region='LON')
    {
        $service = $this->client->queuesService($serviceName, $region);
        $service->setClientId();
        return $service->getQueue($queue);
    }

    /**
     * @param $messages
     * @return array
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
        }
        return $response;
    }


}