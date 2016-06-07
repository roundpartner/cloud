<?php

namespace RoundPartner\Cloud;

class CloudFactory
{

    /**
     * Creates instance of cloud
     *
     * @param string $username
     * @param string $apiKey
     * @param string $secret
     *
     * @return Cloud
     */
    public static function create($username, $apiKey, $secret)
    {
        $client = new Service\Cloud($username, $apiKey);
        return new Cloud($client, $secret);
    }
}
