<?php

namespace RoundPartner\Cloud\Domain;

use OpenCloud\Rackspace;

class DomainFactory
{

    /**
     * @param Rackspace $client
     *
     * @return Domain
     */
    public static function create($client)
    {
        return new Domain($client);
    }
}
