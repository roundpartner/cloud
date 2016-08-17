<?php

namespace RoundPartner\Cloud\Message\Entity;

class ErrorMessage extends Message
{
    const TYPE = 'error';

    /**
     * @param string $content
     * @param string $type
     *
     * @return ErrorMessage
     */
    public static function factory($content, $type = self::TYPE)
    {
        return parent::factory($content, $type);
    }
}
