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
     * @return Queue
     */
    public static function create($client, $secret, $queue, $serviceName = 'cloudQueues', $region = 'LON')
    {
        $service = self::getService($client, $serviceName, $region);
        if ($service->hasQueue($queue)) {
            $queueInstance = $service->getQueue($queue);
            return new Queue($queueInstance, $secret);
        }
        $queueInstance = $service->createQueue($queue);
        return new Queue($queueInstance, $secret);
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
