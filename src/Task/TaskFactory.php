<?php

namespace RoundPartner\Cloud\Task;

use RoundPartner\Cloud\Task\Entity\Task;

class TaskFactory
{
    const DEFAULT_VERSION = 5;

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
        $task->version = self::DEFAULT_VERSION;
        $task->priority = $task::NORMAL_PRIORITY;
        return $task;
    }

    /**
     * Create an invoice task that is used for generating invoices
     *
     * @param int $userId
     * @param int $invoiceId
     * @param string $bucket
     *
     * @return Task
     */
    public static function invoice($userId, $invoiceId, $bucket = null)
    {
        $arguments = array(
            "--user={$userId}",
            "--invoice={$invoiceId}",
        );

        if ($bucket) {
            $arguments[] = "--bucket={$bucket}";
        }

        return self::create(
            'pdf invoice [user_id=' . $userId . ', invoiceId=' . $invoiceId . ' bucket='. $bucket .']',
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
            ),
            true
        );
    }

    /**
     * @param string $flowId
     *
     * @return Task
     */
    public static function goCardlessComplete($flowId)
    {
        return self::create(
            'cardless complete [flow=' . $flowId . ']',
            'cardless',
            'complete',
            array (
                "--flow={$flowId}",
            )
        );
    }

    /**
     * @param int $accountId
     * @param int $customerId
     *
     * @return Task
     */
    public static function sendMandateEmail($accountId, $customerId)
    {
        return self::create(
            'mailing sendMandate [account=' . $accountId . ', customer=' . $customerId . ']',
            'mailing',
            'sendMandate',
            array (
                "--account={$accountId}",
                "--customer={$customerId}",
            )
        );
    }

    /**
     * @param int $userId
     * @param string $bucket
     * @param string $file
     * @param string $type
     * @param string $jobType
     *
     * @return Task
     */
    public static function importCustomers($userId, $bucket, $file, $type, $jobType = '')
    {
        return self::create(
            'import customers [user_id=' . $userId . ' bucket=' . $bucket . ' file=' . $file . ' type=' . $type . ' job_type=' . $jobType . ']',
            'import',
            'customers',
            array(
                "--user={$userId}",
                "--bucket={$bucket}",
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

    /**
     * @param string $userId
     * @param string $template
     * @param string $params
     *
     * @return Task
     *
     * @deprecated
     */
    public static function sendEmail($userId, $template, $params)
    {
        $params = base64_encode(serialize($params));

        return self::create(
            'queue email [user_id=' . $userId . ' template=' . $template . ']',
            'mailing',
            'sendEmail',
            array(
                '--user=' . $userId,
                '--template=' . base64_encode($template),
                '--params=' . $params
            )
        );
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public static function addQuotes($string)
    {
        return escapeshellarg($string);
    }
}
