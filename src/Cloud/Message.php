<?php

namespace RoundPartner\Cloud;

use RoundPartner\VerifyHash\VerifyHash;

class Message
{

    /**
     * @var \OpenCloud\Queues\Resource\Message
     */
    protected $message;

    /**
     * @var
     */
    protected $secret;

    /**
     * Message constructor.
     *
     * @param \OpenCloud\Queues\Resource\Message $message
     * @param string $secret
     */
    public function __construct($message, $secret)
    {
        $this->message = $message;
        $this->secret = $secret;
    }

    public function getBody()
    {
        $body = $this->message->getBody();
        if (isset($body->serial)) {
            $verifyHash = new VerifyHash($this->secret);
            if ($verifyHash->verify($body->sha1, $body->serial)) {
                return unserialize($body->serial);
            } else {
                throw new \Exception('Message secret could not be verified');
            }
        } else {
            throw new \Exception('Message has no secret');
        }
    }

    public function delete()
    {
        $this->message->delete($this->message->getClaimIdFromHref());
    }
}
