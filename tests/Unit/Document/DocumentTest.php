<?php

namespace RoundPartner\Unit\Document;

use RoundPartner\Cloud\Document\Document;

class DocumentTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $this->assertInstanceOf('RoundPartner\Cloud\Document\Document', new Document());
    }
}
