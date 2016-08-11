<?php

namespace RoundPartner\Test\Unit\Task;

use RoundPartner\Cloud\Task\Entity\Task;
use RoundPartner\Cloud\Task\TaskFactory;

class TaskFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Task
     */
    protected $task;

    public function setUp()
    {
        $this->task = TaskFactory::create('taskname', 'command', 'action', array());
    }

    public function testCreate()
    {
        $this->assertInstanceOf('\RoundPartner\Cloud\Task\Entity\Task', $this->task);
    }

    public function testInvoice()
    {
        $invoice = TaskFactory::invoice(123, 456, 'example');
        $this->assertEquals($invoice->taskName, 'pdf invoice');
    }

    public function testCloudBackUpAsExcel()
    {
        $task = TaskFactory::cloudBackupAsExcel(1, 'test-container-name');
        $this->assertEquals($task->taskName, 'excel backup to cloud');
    }

    public function testImportCustomers()
    {
        $task = TaskFactory::importCustomers(1, 'test-container-name', 'file.csv', 'customers', 'Job');
        $this->assertEquals($task->taskName, 'import customers');
    }

    public function testEmailUserResetPassword()
    {
        $task = TaskFactory::emailUserResetPassword(1);
        $this->assertEquals($task->taskName, 'reset user password');
    }
}
