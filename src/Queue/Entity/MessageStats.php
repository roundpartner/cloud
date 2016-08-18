<?php

namespace RoundPartner\Cloud\Queue\Entity;

class MessageStats
{

    /**
     * @var int
     */
    public $age;

    /**
     * @var string
     */
    public $href;

    /**
     * @var string
     */
    public $created;

    /**
     * @param object $stats
     *
     * @return MessageStats
     */
    public static function factory($stats)
    {
        $entity = new static();
        $entity->age = $stats->age;
        $entity->href = $stats->href;
        $entity->created = $stats->created;
        return $entity;
    }
}
