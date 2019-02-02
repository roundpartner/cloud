<?php

namespace RoundPartner\Cloud\Queue\Entity;

use RoundPartner\Cloud\Task\Entity\Task;

class SqsMessage
{
    public $task;

    function __construct($json)
    {
        $object = json_decode($json);

        if (isset($object->Message)) {
            $object = json_decode($object->Message);
        }

        $this->task = $this->createTask($object);
    }

    private function createTask($object)
    {
        $message = new Task();
        $message->version = $object->version;
        $message->taskName = $object->taskName;
        $message->fork = $object->fork;
        $message->command = $object->command;
        $message->arguments = $object->arguments;
        $message->action = $object->action;
        if (isset($object->next) && $object->next !== null) {
            $message->next = $this->createTask($object->next);
        }
        return $message;
    }

    public function getBody() {
        return $this->task;
    }
}