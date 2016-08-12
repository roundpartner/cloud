<?php

namespace RoundPartner\Cloud\Message;

use OpenCloud\Common\Collection\PaginatedIterator;

class MessageService
{
    /**
     * @var \RoundPartner\Cloud\Queue
     */
    protected $queue;

    /**
     * @param \RoundPartner\Cloud\Queue $queue
     */
    public function __construct($queue)
    {
        $this->queue = $queue;
    }

    /**
     * @param mixed $message
     *
     * @return bool
     */
    public function post($message)
    {
        $result = $this->queue->addMessage($message);
        if ($result instanceof PaginatedIterator) {
            // @todo Figure out how this needs to be handled
            return true;
        }
        return $result;
    }

    /**
     * @return Message[]
     */
    public function get()
    {
        $messages = $this->queue->getMessages();
        $returnArray = array();
        foreach ($messages as $message) {
            $returnArray[] = $message->getBody();
            $message->delete();
        }
        return $returnArray;
    }
}
