<?php

namespace Cloud;

class Cloud
{

    /**
     * @var \OpenCloud\Rackspace
     */
    protected $client;

    public function __construct($username, $apiKey)
    {
        $this->client = new \OpenCloud\Rackspace(\OpenCloud\Rackspace::UK_IDENTITY_ENDPOINT, array(
            'username' => $username,
            'apiKey' => $apiKey,
        ));
    }


}