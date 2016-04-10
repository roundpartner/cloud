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
     * @var int
     */
    protected $maxIterations;

    /**
     * @var $sleepTime
     */
    protected $sleepTime;

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
        $this->maxIterations = 2;
        $this->timeLastPolled = 0;
        $this->sleepTime = 1;
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
            $this->delayIteration($iterations);
            $messages = $this->queue->getMessages();
            $iterations++;
        } while (0 === count($messages) && $iterations < $this->maxIterations);

        return $messages;
    }

    /**
     * @param int $iteration
     *
     * @return int
     */
    private function delayIteration($iteration)
    {
        if ($iteration > 0) {
            return 0;
        }
        return sleep($this->sleepTime);
    }

    /**
     * @return int
     */
    public function currentIteration()
    {
        return $this->iterations;
    }
}
