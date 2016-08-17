<?php

namespace RoundPartner\Cloud\Message\Entity;

class Message
{
    /**
     * @var string
     */
    public $content;

    /**
     * @var string
     */
    public $type;

    /**
     * @param string $content
     * @param string $type
     *
     * @return Message
     */
    public static function factory($content, $type)
    {
        $message = new static();
        $message->content = $content;
        $message->type = $type;
        return $message;
    }
}
