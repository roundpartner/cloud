<?php

namespace RoundPartner\Cloud;

interface CloudInterface
{

    /**
     * Cloud constructor.
     *
     * @param Service\Cloud $client
     * @param \GuzzleHttp\Client $awsClient
     * @param string $secret
     */
    public function __construct($client, \GuzzleHttp\Client $awsClient, $secret);
}
