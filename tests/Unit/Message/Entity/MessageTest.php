<?php

namespace RoundPartner\Unit\Message\Entity;

use RoundPartner\Cloud\Message\Entity\ErrorMessage;
use RoundPartner\Cloud\Message\Entity\Message;
use RoundPartner\Cloud\Message\Entity\SuccessMessage;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateMessage()
    {
        $result = Message::factory('hello world', 'test');
        $this->assertInstanceOf('RoundPartner\Cloud\Message\Entity\Message', $result);
    }

    public function testCreateMessageSetsMessage()
    {
        $result = Message::factory('hello world', 'test');
        $this->assertEquals('hello world', $result->content);
    }

    public function testCreateMessageSetsType()
    {
        $result = Message::factory('hello world', 'test');
        $this->assertEquals('test', $result->type);
    }

    public function testCreateSuccessMessage()
    {
        $result = SuccessMessage::factory('hello world');
        $this->assertInstanceOf('RoundPartner\Cloud\Message\Entity\SuccessMessage', $result);
    }

    public function testCreateSuccessMessageSetsType()
    {
        $result = SuccessMessage::factory('hello world');
        $this->assertEquals('success', $result->type);
    }

    public function testCreateErrorMessageSetsType()
    {
        $result = ErrorMessage::factory('hello world');
        $this->assertEquals('error', $result->type);
    }
}
