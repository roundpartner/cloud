<?php

namespace RoundPartner\Cloud\Queue;

use RoundPartner\Cloud\Queue;

class AwsQueueFactory
{
    /**
     * @param \GuzzleHttp\Client $client
     * @param string $secret
     * @param string $queue
     *
     * @throws \Exception
     *
     * @return Queue
     */
    public static function create($client, $secret, $queue)
    {
        $queueInstance = self::createQueueInstance($client, $queue);
        return new Queue($queueInstance, $secret);
    }

    /**
     * @param \GuzzleHttp\Client $client
     * @param string $queue
     *
     * @throws \Exception
     *
     * @return SeqQueue
     */
    private static function createQueueInstance($client, $queue)
    {
        return new SeqQueue($client, $queue);
    }
}
