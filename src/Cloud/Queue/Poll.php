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
     * @var \RoundPartner\Cloud\Queue\Entity\Poll
     */
    protected $config;

    /**
     * Poll constructor.
     *
     * @param \RoundPartner\Cloud\Queue\Entity\Poll $config
     */
    public function __construct(Entity\Poll $config)
    {
        $this->queue = $config->queue;
        $this->messages = array();
        $this->iterations = 0;
        $this->polls = 0;
        $this->timeLastPolled = 0;
        $this->config = $config;
    }

    /**
     * @return Message
     */
    public function next()
    {
        $this->hasNext();
        $this->iterations++;
        return array_shift($this->messages);
    }

    /**
     * @return bool
     */
    public function hasNext()
    {
        if (0 === count($this->messages)) {
            $this->messages = $this->pollQueue();
        }

        return count($this->messages) > 0;
    }

    /**
     * @return Message[]
     */
    private function pollQueue()
    {
        $messages = array();
        if ($this->isMaxTimeReached()) {
            return $messages;
        }

        do {
            $messages = $this->queue->getMessages();
        } while (0 === count($messages) && !$this->isMaxTimeReached() && $this->delayIteration());

        return $messages;
    }

    private function isMaxTimeReached()
    {
        $timeNow = time();
        $timeRemaining =  $timeNow - $this->config->endTime;
        return $timeRemaining > 0;
    }

    /**
     * @return bool
     */
    private function delayIteration()
    {
        return 0 === sleep($this->config->sleepTime);
    }

    /**
     * @return int
     */
    public function currentIteration()
    {
        return $this->iterations;
    }
}
