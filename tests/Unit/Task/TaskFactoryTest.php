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

    public function testTaskHasAVersion()
    {
        $this->assertEquals(1, $this->task->version);
    }

    public function testCreateDoesNotForkByDefault()
    {
        $this->assertFalse($this->task->fork);
    }

    public function testCanFork()
    {
        $this->task = TaskFactory::create('taskname', 'command', 'action', array(), true);
        $this->assertTrue($this->task->fork);
    }

    public function testInvoice()
    {
        $invoice = TaskFactory::invoice(42, 456, 'example');
        $this->assertEquals($invoice->taskName, 'pdf invoice [user_id=42, invoiceId=456]');
    }

    public function testCloudBackUpAsExcel()
    {
        $task = TaskFactory::cloudBackupAsExcel(42, 'test-container-name');
        $this->assertEquals($task->taskName, 'excel backup to cloud [user_id=42]');
    }

    public function testCloudBackUpAsExcelRunsAsForkedProcess()
    {
        $task = TaskFactory::cloudBackupAsExcel(42, 'test-container-name');
        $this->assertTrue($task->fork);
    }

    public function testImportCustomers()
    {
        $task = TaskFactory::importCustomers(42, 'test-container-name', 'file.csv', 'customers', 'Job');
        $this->assertEquals($task->taskName, 'import customers [user_id=42 container=test-container-name file=file.csv type=customers job_type=Job]');
    }

    public function testEmailUserResetPassword()
    {
        $task = TaskFactory::emailUserResetPassword(42);
        $this->assertEquals($task->taskName, 'reset user password [user_id=42]');
    }

    public function testGoCardlessComplete()
    {
        $customer = TaskFactory::goCardlessComplete('example_flow_id');
        $this->assertEquals($customer->taskName, 'cardless complete [flow=example_flow_id]');
    }

    public function testSendMandateEmail()
    {
        $mandate = TaskFactory::sendMandateEmail(123, 321);
        $this->assertEquals($mandate->taskName, 'mailing sendMandate [account=123, customer=321]');
    }

    public function testiftttUserRegistered()
    {
        $email = TaskFactory::iftttUserRegistered('test', 'example account name', 'some@email.com');
        $this->assertEquals($email->taskName, 'user registered [user_id=\'test\' template=\'example account name\']');
    }

    public function testiftttUserRegisteredNameIsQuoted()
    {
        $email = TaskFactory::iftttUserRegistered('test', 'example account name', 'some@email.com');
        $this->assertContains('--username=\'test\'', $email->arguments);
    }

    public function testiftttUserRegisteredBusinessIsQuoted()
    {
        $email = TaskFactory::iftttUserRegistered('test', 'example account name', 'some@email.com');
        $this->assertContains('--account=\'example account name\'', $email->arguments);
    }

    public function testiftttUserRegisteredEmailIsQuoted()
    {
        $email = TaskFactory::iftttUserRegistered('test', 'example account name', 'some@email.com');
        $this->assertContains('--email=\'some@email.com\'', $email->arguments);
    }

    public function testSendEmail()
    {
        $email = TaskFactory::sendEmail('test', 'welcome', []);
        $this->assertEquals($email->taskName, 'queue email [user_id=test template=welcome]');
    }
}
