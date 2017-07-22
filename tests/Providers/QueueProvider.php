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
        "serial": "a:0:{}",
        "sha1": "87b0509503c94fffef003156afa80d9bc40c99e2"
      },
      "age": 296,
      "href": "/v1/queues/demoqueue/messages/51db6f78c508f17ddc924357?claim_id=51db7067821e727dc24df754",
      "ttl": 300
    }
]
BODY;
        return [[$body, 200]];
    }

    public static function messages()
    {
        $body = <<<BODY
[
    {
      "body": {
        "serial": "a:0:{}",
        "sha1": "87b0509503c94fffef003156afa80d9bc40c99e2"
      },
      "age": 296,
      "href": "/v1/queues/demoqueue/messages/51db6f78c508f17ddc924357?claim_id=51db7067821e727dc24df754",
      "ttl": 300
    },
    {
      "body": {
        "serial": "a:0:{}",
        "sha1": "87b0509503c94fffef003156afa80d9bc40c99e2"
      },
      "age": 296,
      "href": "/v1/queues/demoqueue/messages/51db6f78c508f17ddc924357?claim_id=51db7067821e727dc24df754",
      "ttl": 300
    },
    {
      "body": {
        "serial": "a:0:{}",
        "sha1": "87b0509503c94fffef003156afa80d9bc40c99e2"
      },
      "age": 296,
      "href": "/v1/queues/demoqueue/messages/51db6f78c508f17ddc924357?claim_id=51db7067821e727dc24df754",
      "ttl": 300
    },
    {
      "body": {
        "serial": "a:0:{}",
        "sha1": "87b0509503c94fffef003156afa80d9bc40c99e2"
      },
      "age": 296,
      "href": "/v1/queues/demoqueue/messages/51db6f78c508f17ddc924357?claim_id=51db7067821e727dc24df754",
      "ttl": 300
    },
    {
      "body": {
        "serial": "a:0:{}",
        "sha1": "87b0509503c94fffef003156afa80d9bc40c99e2"
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
