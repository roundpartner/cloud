<?php

namespace RoundPartner\Cloud\Queue;

use RoundPartner\Cloud\QueueInterface;

class PollFactory
{

    const SECOND = 1;
    const MINUTE = 60;

    /**
     * @param QueueInterface $queue
     * @param int $runTime
     * @param int $sleepTime
     *
     * @return Poll
     */
    public static function create(QueueInterface $queue, $runTime = self::MINUTE, $sleepTime = self::SECOND)
    {
        $config = new Entity\Poll();
        $config->queue = $queue;
        $config->sleepTime = $sleepTime;
        $config->runTime = $runTime;
        $config->endTime = time() + $config->runTime;
        return new Poll($config);
    }
}
