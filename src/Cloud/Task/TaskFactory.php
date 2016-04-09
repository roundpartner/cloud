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
    public static function create($taskName, $command, $action = null, $arguments = array())
    {
        $task = new Task();
        $task->taskName = $taskName;
        $task->command = $command;
        $task->action = $action;
        $task->arguments = $arguments;
        return $task;
    }

    /**
     * Create an invoice task that is used for generating invoices
     *
     * @param int $accountId
     * @param int $invoiceId
     * @param string $container
     *
     * @return Task
     */
    public static function invoice($accountId, $invoiceId, $container = null)
    {
        $arguments = array(
            "--account={$accountId}",
            "--invoice={$invoiceId}",
        );
        
        if ($container) {
            $arguments[] = "--container={$container}";
        }

        return self::create(
            'pdf invoice',
            'pdf',
            'invoice',
            $arguments
        );
    }
}
