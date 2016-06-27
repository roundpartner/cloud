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
    public static function create($client)
    {
        return new Document($client);
    }
}
