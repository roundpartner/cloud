<?php

namespace Cloud;

interface CloudInterface
{

    /**
     * Cloud constructor.
     *
     * @param string $username
     * @param string $apiKey
     * @param string $secret
     */
    public function __construct($username, $apiKey, $secret);

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