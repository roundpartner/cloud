<?php

namespace RoundPartner\Cloud\Document;

use OpenCloud\Rackspace;

class DocumentFactory
{
    /**
     * @param Rackspace $client
     *
     * @return Document
     */
    public static function create($client, $region = 'LON')
    {
        return new Document($client, $region);
    }
}
