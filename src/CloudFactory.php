<?php

namespace RoundPartner\Cloud;

use GuzzleHttp\Client;

class CloudFactory
{

    /**
     * Creates instance of cloud
     *
     * @param string $username
     * @param string $apiKey
     * @param string $secret
     * @param string $aws
     *
     * @return Cloud
     */
    public static function create($username, $apiKey, $secret, $aws = "localhost")
    {
        $client = new Service\Cloud($username, $apiKey);
        $awsClient = new Client([
            'base_uri' => "http://{$aws}:6767",
        ]);

        return new Cloud($client, $awsClient, $secret);
    }
}
