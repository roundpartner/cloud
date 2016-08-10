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

    /**
     * @param int $userId
     * @param string $container
     *
     * @return Task
     */
    public static function cloudBackupAsExcel($userId, $container)
    {
        return self::create(
            'excel backup to cloud',
            'backUp',
            'asExcel',
            array (
                "--clientId={$userId}",
                "--container={$container}",
            )
        );
    }

    /**
     * @param int $userId
     * @param string $container
     * @param string $file
     * @param string $type
     * @param string $jobType
     *
     * @return Task
     */
    public static function importCustomers($userId, $container, $file, $type, $jobType = '')
    {
        return self::create(
            'import customers',
            'import',
            'customers',
            array(
                "--user={$userId}",
                "--container={$container}",
                "--file={$file}",
                "--type={$type}",
                "--jobType={$jobType}",
            )
        );
    }
}
