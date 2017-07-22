<?php

namespace RoundPartner\Tests\Providers;

class QueueProvider
{
    public static function message()
    {
        $body = <<<BODY
[
    {
      "body": {
        "event": "BackupStarted"
      },
      "age": 296,
      "href": "/v1/queues/demoqueue/messages/51db6f78c508f17ddc924357?claim_id=51db7067821e727dc24df754",
      "ttl": 300
    }
]
BODY;
        return [[$body, 200]];
    }
}
