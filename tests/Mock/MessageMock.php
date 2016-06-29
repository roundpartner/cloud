<?php

namespace RoundPartner\Tests\Mock;

class MessageMock
{

    /**
     * @var array
     */
    protected $message;

    public function __construct($message = array())
    {
        $this->message = $message;
    }

    public function getBody()
    {
        return (object) $this->message;
    }
}
