<?php

namespace RoundPartner\Cloud\Document;

use OpenCloud\Rackspace;

class DocumentFactory
{
    /**
     * @param Rackspace $client
     * @param string $region
     *
     * @return Document
     */
    public static function create($client, $region = 'LON')
    {
        return new Document($client, $region);
    }
}
