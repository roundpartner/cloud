<?php

namespace RoundPartner\Unit\Message;

use RoundPartner\Cloud\Message\Message;
use RoundPartner\Tests\Mock\MessageMock;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    public function testMessageSecretNotVerifiable()
    {
        $mock = new MessageMock(array('sha1' => 'hello world', 'serial' => 'hello world'));
        $message = new Message($mock, 'no secret');
        $this->setExpectedException('\RoundPartner\Cloud\Message\InvalidSignatureException', 'Message could not be verified');
        $message->getBody();
    }

    public function testMessageHasNoSecret()
    {
        $mock = new MessageMock();
        $message = new Message($mock, 'no secret');
        $this->setExpectedException('\RoundPartner\Cloud\Message\NoSignatureException', 'Message has no signature');
        $message->getBody();
    }
}
