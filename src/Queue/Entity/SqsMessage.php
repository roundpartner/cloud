<?php

namespace RoundPartner\Cloud\Queue\Entity;

use RoundPartner\Cloud\Task\Entity\Task;

class SqsMessage
{
    public $task;

    function __construct($json)
    {
        $object = json_decode($json);

        $message = new Task();
        $message->version = $object->version;
        $message->taskName = $object->taskName;
        $message->fork = $object->fork;
        $message->command = $object->command;
        $message->arguments = $object->arguments;
        $message->action = $object->action;
        $this->task = $message;
    }

    public function getBody() {
        return $this->task;
    }
}