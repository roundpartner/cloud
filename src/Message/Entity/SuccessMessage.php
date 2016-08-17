<?php

namespace RoundPartner\Cloud\Message\Entity;

class SuccessMessage extends Message
{
    const TYPE = 'success';

    /**
     * @param string $content
     * @param string $type
     *
     * @return SuccessMessage
     */
    public static function factory($content, $type = self::TYPE)
    {
        return parent::factory($content, $type);
    }
}
