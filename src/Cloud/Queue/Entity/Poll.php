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
}
