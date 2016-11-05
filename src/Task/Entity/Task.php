<?php

namespace RoundPartner\Cloud\Task\Entity;

class Task
{
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
}
