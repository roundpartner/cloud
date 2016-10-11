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
     * @param bool $fork
     *
     * @return Task
     */
    public static function create($taskName, $command, $action = null, $arguments = array(), $fork = false)
    {
        $task = new Task();
        $task->taskName = $taskName;
        $task->command = $command;
        $task->action = $action;
        $task->arguments = $arguments;
        $task->fork = $fork;
        return $task;
    }

    /**
     * Create an invoice task that is used for generating invoices
     *
     * @param int $userId
     * @param int $invoiceId
     * @param string $container
     *
     * @return Task
     */
    public static function invoice($userId, $invoiceId, $container = null)
    {
        $arguments = array(
            "--user={$userId}",
            "--invoice={$invoiceId}",
        );

        if ($container) {
            $arguments[] = "--container={$container}";
        }

        return self::create(
            'pdf invoice [user_id=' . $userId . ', invoiceId=' . $invoiceId . ']',
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
            'excel backup to cloud [user_id=' . $userId . ']',
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
            'import customers [user_id=' . $userId . ']',
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

    /**
     * @param int $userId
     *
     * @return Task
     */
    public static function emailUserResetPassword($userId)
    {
        return self::create(
            'reset user password [user_id=' . $userId . ']',
            'user',
            'reset',
            array("--user={$userId}")
        );
    }
}
