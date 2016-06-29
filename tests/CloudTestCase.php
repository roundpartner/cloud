<?php

namespace RoundPartner\Tests;

use OpenCloud\Rackspace;
use OpenCloud\Tests\OpenCloudTestCase;
use RoundPartner\Cloud\Service\Cloud;

class CloudTestCase extends OpenCloudTestCase
{
    public function newClient()
    {
        return new Cloud(Rackspace::US_IDENTITY_ENDPOINT, array(
            'username' => 'foo',
            'apiKey'   => 'bar'
        ));
    }
}
