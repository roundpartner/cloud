<?php

namespace Cloud\Service;

use OpenCloud\Rackspace;

class Cloud extends Rackspace implements ServiceInterface
{

    public function __construct($username, $apiKey)
    {
        parent::__construct(
            \OpenCloud\Rackspace::UK_IDENTITY_ENDPOINT,
            array(
                'username' => $username,
                'apiKey' => $apiKey,
            )
        );
    }
}
