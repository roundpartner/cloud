<?php

namespace RoundPartner\Cloud\Queue;

use RoundPartner\Cloud\QueueInterface;

class PollFactory
{

    /**
     * @param QueueInterface $queue
     *
     * @return Poll
     */
    public static function create(QueueInterface $queue)
    {
        $config = new Entity\Poll();
        $config->queue = $queue;
        $config->sleepTime = 1;
        $config->runTime = 10;
        $config->endTime = time() + $config->runTime;
        return new Poll($config);
    }
}
