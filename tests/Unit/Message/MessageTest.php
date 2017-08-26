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

    public function testUnserialiseBody()
    {
        $mock = new MessageMock();
        $message = new Message($mock, 'no secret');
        $serialised = 's:11:"Hello World";';
        $result = $this->invokeMethod($message, 'unserialiseBody', [$serialised]);
        $this->assertEquals('Hello World', $result);
    }

    public function testUnserialiseBodyExcepts()
    {
        $this->setExpectedException('\Exception', 'Unable to unserialise message');
        $mock = new MessageMock();
        $message = new Message($mock, 'no secret');
        $serialised = 'O:35:"\RoundPartner\Cloud\Massage\Massage":1:{s:7:"massage";a:0:{}}';
        $this->invokeMethod($message, 'unserialiseBody', [$serialised]);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
