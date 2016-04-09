<?php

namespace RoundPartner\Test\Mock;

use RoundPartner\Cloud\QueueInterface;

class QueueMock implements QueueInterface
{
    
    protected $messages;
    
    public function __construct()
    {
        $this->messages = array();
    }

    /**
     * @param mixed $message
     * @param int $ttl
     *
     * @return bool
     */
    public function addMessage($message, $ttl = 600)
    {
        $this->messages[] = $message;
        return true;
    }

    /**
     * @param integer $limit
     *
     * @return mixed[]
     *
     * @throws \Exception
     */
    public function getMessages($limit = 10)
    {
        return array_splice($this->messages, 0, $limit);
    }
}
