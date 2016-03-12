<?php

namespace RoundPartner\Cloud;

interface CloudInterface
{

    /**
     * Cloud constructor.
     *
     * @param Service\Cloud $client
     * @param string $secret
     */
    public function __construct(Service\Cloud $client, $secret);

    /**
     * @param string $queue
     * @param mixed $message
     * @return bool
     */
    public function addMessage($queue, $message);

    /**
     * @param string $queue
     * @param integer $limit
     *
     * @return mixed[]
     * @throws \Exception
     */
    public function getMessages($queue, $limit = 10);
}
