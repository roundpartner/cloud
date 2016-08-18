<?php

namespace RoundPartner\Cloud\Queue\Entity;

class Stats
{
    /**
     * @var int
     */
    public $claimed;

    /**
     * @var int
     */
    public $total;

    /**
     * @var int
     */
    public $free;

    /**
     * @var MessageStats
     */
    public $oldest;

    /**
     * @var MessageStats
     */
    public $newest;

    /**
     * @param object $stats
     *
     * @return Stats
     */
    public static function factory($stats)
    {
        $entity = new static();
        $entity->claimed = $stats->claimed;
        $entity->total = $stats->total;
        $entity->free = $stats->free;
        if (isset($stats->oldest)) {
            $entity->oldest = MessageStats::factory($stats->oldest);
        }
        if (isset($stats->newest)) {
            $entity->newest = MessageStats::factory($stats->newest);
        }
        return $entity;
    }
}
