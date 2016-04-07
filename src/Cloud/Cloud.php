<?php

namespace RoundPartner\Cloud;

use RoundPartner\VerifyHash\VerifyHash;

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
        $queueInstance = $this->getQueue($queue);
        return new Queue($queueInstance, $this->secret);
    }

    /**
     * @param string $queue
     * @param mixed $message
     * @param int $ttl
     *
     * @deprecated use queue method
     *
     * @return bool
     */
    public function addMessage($queue, $message, $ttl = 600)
    {
        return $this->queue($queue)->addMessage($message, $ttl);
    }

    /**
     * @param string $queue
     * @param integer $limit
     *
     * @deprecated use queue method
     *
     * @return mixed[]
     * @throws \Exception
     */
    public function getMessages($queue, $limit = 10)
    {
        return $this->queue($queue)->getMessages($limit);
    }

    /**
     * @param string $queue
     * @param string $serviceName
     * @param string $region
     *
     * @return \OpenCloud\Queues\Resource\Queue
     */
    protected function getQueue($queue, $serviceName = 'cloudQueues', $region = 'LON')
    {
        $service = $this->client->queuesService($serviceName, $region);
        $service->setClientId();
        return $service->getQueue($queue);
    }
}
