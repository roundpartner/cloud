<?php

namespace RoundPartner\Cloud;

interface QueueInterface
{

    /**
     * @param mixed $message
     * @param int $ttl
     *
     * @return bool
     */
    public function addMessage($message, $ttl = 600);

    /**
     * @param integer $limit
     *
     * @return mixed[]
     *
     * @throws \Exception
     */
    public function getMessages($limit = 10);
}
