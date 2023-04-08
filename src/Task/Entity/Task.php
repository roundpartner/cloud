<?php

namespace RoundPartner\Cloud\Task\Entity;

class Task
{
    const HIGH_PRIORITY = 'high';
    const NORMAL_PRIORITY = 'normal';

    /**
     * @var string
     */
    public $taskName;

    /**
     * @var string
     */
    public $command;

    /**
     * @var string
     */
    public $action;

    /**
     * @var string[]
     */
    public $arguments;

    /**
     * @var bool
     */
    public $fork;

    /**
     * @var int
     */
    public $version;

    /**
     * @var Task
     */
    public $next;

    /**
     * @var string
     */
    public $priority;

    /**
     * @return $this
     */
    public function setHighPriority()
    {
        $this->priority = self::HIGH_PRIORITY;
        return $this;
    }
}
