<?php

namespace RoundPartner\Cloud\Queue;

use RoundPartner\Cloud\QueueInterface;

class PollFactory
{

    const SECOND = 1;
    const MINUTE = 60;

    const DEFAULT_MESSAGE_BUFFER = 10;

    /**
     * @param QueueInterface $queue
     * @param int $runTime
     * @param int $sleepTime
     * @param int $maxMessageBuffer
     *
     * @return Poll
     */
    public static function create(
        QueueInterface $queue,
        $runTime = self::MINUTE,
        $sleepTime = self::SECOND,
        $maxMessageBuffer = self::DEFAULT_MESSAGE_BUFFER
    ) {
        $config = new Entity\Poll();
        $config->queue = $queue;
        $config->sleepTime = $sleepTime;
        $config->runTime = $runTime;
        $config->endTime = time() + $config->runTime;
        $config->maxMessageBuffer = $maxMessageBuffer;
        return new Poll($config);
    }
}
