<?php

namespace RoundPartner\Unit\Task;

use RoundPartner\Cloud\Entity\Task;
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
        $this->assertInstanceOf('\RoundPartner\Cloud\Entity\Task', $this->task);
    }
}
