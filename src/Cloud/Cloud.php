<?php

namespace RoundPartner\Cloud;

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
            $queueInstance = $this->getQueue($queue);
            $this->queueServices[$queue] = new Queue($queueInstance, $this->secret);
        }
        return $this->queueServices[$queue];
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
     *
     * @throws \Exception
     */
    public function getMessages($queue, $limit = 10)
    {
        $response = array();
        $messages = $this->queue($queue)->getMessages($limit);
        foreach ($messages as $message) {
            $response[] = $message->getBody();
            $message->delete();
        }
        return $response;
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
