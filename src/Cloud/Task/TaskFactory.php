<?php

namespace RoundPartner\Cloud\Task;

use RoundPartner\Cloud\Task\Entity\Task;

class TaskFactory
{
    /**
     * @param string $taskName
     * @param string $command
     * @param string $action
     * @param string[] $arguments
     *
     * @return Task
     */
    public static function create($taskName, $command, $action, $arguments)
    {
        $task = new Task();
        $task->taskName = $taskName;
        $task->command = $command;
        $task->action = $action;
        $task->arguments = $arguments;
        return $task;
    }
}
