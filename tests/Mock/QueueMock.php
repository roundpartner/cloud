<?php

namespace RoundPartner\Tests\Mock;

use RoundPartner\Cloud\QueueInterface;

class QueueMock implements QueueInterface
{

    /**
     * @var array
     */
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
     * @param integer $grace
     * @param integer $ttl
     *
     * @return mixed[]
     *
     * @throws \Exception
     */
    public function getMessages($limit = 10, $grace = 0, $ttl = 0)
    {
        return array_splice($this->messages, 0, $limit);
    }
}
