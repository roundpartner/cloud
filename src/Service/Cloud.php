<?php

namespace RoundPartner\Cloud\Service;

use OpenCloud\Rackspace;

class Cloud extends Rackspace implements ServiceInterface
{

    /**
     * Cloud constructor.
     *
     * @param string $username
     * @param string $apiKey
     * @param $endPointUrl $apiKey
     */
    public function __construct($username, $apiKey, $endPointUrl = Rackspace::UK_IDENTITY_ENDPOINT)
    {
        parent::__construct(
            $endPointUrl,
            array(
                'username' => $username,
                'apiKey' => $apiKey,
            )
        );
    }
}
