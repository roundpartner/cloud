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
        $service = $client->queuesService($serviceName, $region);
        $service->setClientId();
        $service->getClient()->getConfig()->set('curl.options', array('body_as_string' => true));
        $queueInstance = $service->getQueue($queue);
        return new Queue($queueInstance, $secret);
    }
}
