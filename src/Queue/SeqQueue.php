<?php

namespace RoundPartner\Cloud\Queue;

use GuzzleHttp\Client;
use RoundPartner\Cloud\Queue\Entity\Stats;

class SeqQueue
{
    protected $queue;

    /**
     * @var Client
     */
    protected $client;

    function __construct($queue)
    {
        $this->queue = $queue;
        $this->client = new Client([
            'base_uri' => 'http://localhost:6767',
        ]);
    }

    /**
     * Post an individual message.
     *
     * @param array $params
     * @return bool
     */
    public function createMessage(array $params)
    {
        $response = $this->client->post('', [
            'content' => json_encode($params)
        ]);
        if (204 !== $response->getStatusCode()) {
            return false;
        }
        return true;
    }

    /**
     * This operation claims a set of messages, up to limit, from oldest to
     * newest, skipping any that are already claimed. If no unclaimed messages
     * are available, FALSE is returned.
     *
     * You should delete the message when you have finished processing it,
     * before the claim expires, to ensure the message is processed only once.
     * Be aware that you must call the delete() method on the Message object and
     * pass in the Claim ID, rather than relying on the service's bulk delete
     * deleteMessages() method. This is so that the server can return an error
     * if the claim just expired, giving you a chance to roll back your processing
     * of the given message, since another worker will likely claim the message
     * and process it.
     *
     * Just as with a message's age, the age given for the claim is relative to
     * the server's clock, and is useful for determining how quickly messages are
     * getting processed, and whether a given message's claim is about to expire.
     *
     * When a claim expires, it is removed, allowing another client worker to
     * claim the message in the case that the original worker fails to process it.
     *
     * @param int $limit
     */
    public function claimMessages(array $options = array())
    {
        $response = $this->client->get('');
        $content = $response->getBody()->getContents();
        $jsonObject = (array) json_decode($content);
        if (empty($jsonObject)) {
            return null;
        }
        if (empty($jsonObject->content)) {
            return null;
        }
        return [$jsonObject->content];
    }

    /**
     * This operation returns queue statistics including how many messages are
     * in the queue, broken out by status.
     *
     * @return object
     */
    public function getStats()
    {
        $stats = new Stats();
        return $stats;
    }

    /**
     * Delete this resource
     *
     * @throws \Exception
     *
     * @return \Guzzle\Http\Message\Response
     */
    public function delete()
    {
        throw new \Exception("Not Implemented");
    }
}