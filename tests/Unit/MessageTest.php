<?php

namespace RoundPartner\Unit;

use RoundPartner\Cloud\Message;
use RoundPartner\Tests\Mock\MessageMock;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    public function testMessageSecretNotVerifiable()
    {
        $mock = new MessageMock(array('sha1' => 'hello world', 'serial' => 'hello world'));
        $message = new Message($mock, 'no secret');
        $this->setExpectedException('\Exception', 'Message secret could not be verified');
        $message->getBody();
    }

    public function testMessageHasNoSecret()
    {
        $mock = new MessageMock();
        $message = new Message($mock, 'no secret');
        $this->setExpectedException('\Exception', 'Message has no secret');
        $message->getBody();
    }
}
