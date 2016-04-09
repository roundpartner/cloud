<?php

namespace RoundPartner\Cloud\Queue;

use RoundPartner\Cloud\QueueInterface;

class Poll
{

    /**
     * @var QueueInterface
     */
    protected $queue;

    /**
     * @var mixed[]
     */
    protected $messages;

    /**
     * @var int
     */
    protected $iterations;

    /**
     * Poll constructor.
     *
     * @param QueueInterface $queue
     */
    public function __construct(QueueInterface $queue)
    {
        $this->queue = $queue;
        $this->messages = array();
        $this->iterations = 0;
    }

    /**
     * @return mixed
     */
    public function next()
    {
        if (0 === count($this->messages)) {
            $this->messages = $this->pollQueue();
        }

        $this->iterations++;
        return array_shift($this->messages);
    }

    private function pollQueue()
    {
        $iterations = 0;
        do {
            $messages = $this->queue->getMessages();
            $iterations++;
        } while (0 === count($messages) && $iterations < 1);

        return $messages;
    }

    /**
     * @return int
     */
    public function currentIteration()
    {
        return $this->iterations;
    }
}