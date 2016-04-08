<?php

namespace RoundPartner\Cloud\Entity;

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
}
