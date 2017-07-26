<?php

namespace RoundPartner\Cloud\Domain;

use OpenCloud\Rackspace;

class DomainFactory
{

    /**
     * @param Rackspace $client
     * @param string $serviceName
     * @param string $region
     *
     * @return Domain
     */
    public static function create($client, $serviceName = 'cloudDNS', $region = 'LON')
    {
        return new Domain($client, $serviceName, $region);
    }
}
