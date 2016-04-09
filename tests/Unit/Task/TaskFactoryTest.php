<?php

namespace RoundPartner\Unit\Task;

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
}
