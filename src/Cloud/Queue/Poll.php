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
    protected $maxTime;

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
        $this->maxTime = 10;
        $this->polls = 0;
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
        $endTime = time() + $this->maxTime;
        do {
            $messages = $this->queue->getMessages();
        } while (0 === count($messages) && $this->isMaxTimeReached($endTime) && $this->delayIteration());

        return $messages;
    }

    private function isMaxTimeReached($endTime)
    {
        $timeNow = time();
        $timeRemaining = $endTime - $timeNow;
        return $timeRemaining > 0;
    }

    /**
     * @return bool
     */
    private function delayIteration()
    {
        return 0 === sleep($this->sleepTime);
    }

    /**
     * @return int
     */
    public function currentIteration()
    {
        return $this->iterations;
    }
}
