<?php

namespace RoundPartner\Cloud\Queue\Entity;

use RoundPartner\Cloud\QueueInterface;

class Poll
{

    /**
     * @var QueueInterface
     */
    public $queue;

    /**
     * @var int
     */
    public $runTime;

    /**
     * @var int
     */
    public $endTime;

    /**
     * @var int
     */
    public $sleepTime;

    /**
     * @var int
     */
    public $maxMessageBuffer;

    /**
     * @var int
     */
    public $grace;

    /**
     * @var int
     */
    public $ttl;
}
