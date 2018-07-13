<?php

namespace RoundPartner\Cloud\Queue;

use RoundPartner\Cloud\Queue;

class QueueFactory
{

    /**
     * @param \OpenCloud\Rackspace $client
     * @param string $secret
     * @param string $queue
     * @param string $serviceName
     * @param string $region
     *
     * @throws \Exception
     *
     * @return Queue
     */
    public static function create($client, $secret, $queue, $serviceName = 'cloudQueues', $region = 'LON')
    {
        $service = self::getService($client, $serviceName, $region);
        $queueInstance = self::createQueueInstance($service, $queue);
        return new Queue($queueInstance, $secret);
    }

    /**
     * @param \OpenCloud\Queues\Service $service
     * @param string $queue
     *
     * @throws \Exception
     *
     * @return Queue
     */
    private static function createQueueInstance($service, $queue)
    {
        if ($service->hasQueue($queue)) {
            return $service->getQueue($queue);
        }
        return $service->createQueue($queue);
    }

    /**
     * @param \OpenCloud\Rackspace $client
     * @param string $serviceName
     * @param string $region
     *
     * @return \OpenCloud\Queues\Service
     */
    private static function queueService($client, $serviceName = 'cloudQueues', $region = 'LON')
    {
        return $client->queuesService($serviceName, $region);
    }

    /**
     * @param \OpenCloud\Rackspace $client
     * @param string $serviceName
     * @param string $region
     *
     * @return \OpenCloud\Queues\Service
     */
    private static function getService($client, $serviceName = 'cloudQueues', $region = 'LON')
    {
        $service = self::queueService($client, $serviceName, $region);
        $service->setClientId();
        $service->getClient()->getConfig()->set('curl.options', array('body_as_string' => true));
        return $service;
    }
}
