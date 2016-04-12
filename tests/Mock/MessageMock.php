<?php

namespace RoundPartner\Test\Mock;

class MessageMock
{
    
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
