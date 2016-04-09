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
            $this->messages = $this->queue->getMessages();
        }

        $this->iterations++;
        return array_shift($this->messages);
    }

    /**
     * @return int
     */
    public function currentIteration()
    {
        return $this->iterations;
    }
}