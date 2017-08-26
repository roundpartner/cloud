<?php

namespace RoundPartner\Cloud\Message;

use RoundPartner\VerifyHash\VerifyHash;

class Message
{

    /**
     * @var \OpenCloud\Queues\Resource\Message
     */
    protected $message;

    /**
     * @var string
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

    /**
     * @return mixed
     *
     * @throws \Exception
     */
    public function getBody()
    {
        $body = $this->message->getBody();
        if (isset($body->serial)) {
            return $this->verifyBody($body);
        }
        throw new NoSignatureException('Message has no signature');
    }

    /**
     * @return bool
     */
    public function delete()
    {
        return $this->message->delete($this->message->getClaimIdFromHref());
    }

    /**
     * @param object $body
     *
     * @return mixed
     *
     * @throws \Exception
     */
    private function verifyBody($body)
    {
        $verifyHash = new VerifyHash($this->secret);
        if ($verifyHash->verify($body->sha1, $body->serial)) {
            return $this->unserialiseBody($body->serial);
        } else {
            throw new InvalidSignatureException('Message could not be verified');
        }
    }

    /**
     * @param string $body
     *
     * @return mixed
     *
     * @throws \Exception
     */
    private function unserialiseBody($body)
    {
        $object = unserialize($body);
        if ($object instanceof __PHP_Incomplete_Class) {
            throw new \Exception('Unable to unserialise message');
        }
        return $object;
    }
}
