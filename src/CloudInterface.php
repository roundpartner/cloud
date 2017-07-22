<?php

namespace RoundPartner\Cloud;

interface CloudInterface
{

    /**
     * Cloud constructor.
     *
     * @param Service\Cloud $client
     * @param string $secret
     */
    public function __construct($client, $secret);
}
