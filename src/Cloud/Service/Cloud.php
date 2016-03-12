<?php

namespace Cloud\Service;

use OpenCloud\Rackspace;

class Cloud extends Rackspace implements ServiceInterface
{

    /**
     * Cloud constructor.
     *
     * @param string $username
     * @param string $apiKey
     */
    public function __construct($username, $apiKey)
    {
        parent::__construct(
            Rackspace::UK_IDENTITY_ENDPOINT,
            array(
                'username' => $username,
                'apiKey' => $apiKey,
            )
        );
    }
}
