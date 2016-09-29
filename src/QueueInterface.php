<?php

namespace RoundPartner\Cloud;

use OpenCloud\Queues\Resource\Claim;

interface QueueInterface
{

    /**
     * @param Message\Message $message
     * @param int $ttl
     *
     * @return bool
     */
    public function addMessage($message, $ttl = 600);

    /**
     * @param int $limit
     * @param int $grace
     * @param int ttl
     *
     * @return Message\Message[]
     *
     * @throws \Exception
     */
    public function getMessages($limit = Claim::LIMIT_DEFAULT, $grace = CLAIM::GRACE_DEFAULT, $ttl = CLAIM::TTL_DEFAULT);
}
