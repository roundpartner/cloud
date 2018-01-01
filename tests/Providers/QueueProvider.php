<?php

namespace RoundPartner\Tests\Providers;

class QueueProvider
{
    public static function domain()
    {
        $body = /** @lang json */ <<<BODY
[{
  "nameservers" : [ {
    "name" : "dns1.stabletransit.com"
  }, {
    "name" : "dns2.stabletransit.com"
  } ],
  "recordsList" : {
    "records" : [ {
      "updated" : "2011-05-19T13:07:08.000+0000",
      "ttl" : 5771,
      "created" : "2011-05-18T19:53:09.000+0000",
      "name" : "ftp.example.com",
      "id" : "A-6817754",
      "type" : "A",
      "data" : "192.0.2.8"
    }, {
      "updated" : "2011-06-24T01:12:52.000+0000",
      "ttl" : 86400,
      "created" : "2011-06-24T01:12:52.000+0000",
      "name" : "example.com",
      "id" : "A-6822994",
      "type" : "A",
      "data" : "192.0.2.17"
    }, {
      "updated" : "2011-06-24T01:12:51.000+0000",
      "ttl" : 3600,
      "created" : "2011-06-24T01:12:51.000+0000",
      "name" : "example.com",
      "id" : "NS-6251982",
      "type" : "NS",
      "data" : "dns1.stabletransit.com"
    }, {
      "updated" : "2011-06-24T01:12:51.000+0000",
      "ttl" : 3600,
      "created" : "2011-06-24T01:12:51.000+0000",
      "name" : "example.com",
      "id" : "NS-6251983",
      "type" : "NS",
      "data" : "dns2.stabletransit.com"
    }, {
      "updated" : "2011-06-24T01:12:53.000+0000",
      "ttl" : 3600,
      "created" : "2011-06-24T01:12:53.000+0000",
      "name" : "example.com",
      "id" : "MX-3151218",
      "priority" : 5,
      "type" : "MX",
      "data" : "mail.example.com"
    }, {
      "updated" : "2011-06-24T01:12:54.000+0000",
      "ttl" : 5400,
      "created" : "2011-06-24T01:12:54.000+0000",
      "name" : "www.example.com",
      "id" : "CNAME-9778009",
      "type" : "CNAME",
      "comment" : "This is a comment on the CNAME record",
      "data" : "example.com"
    } ],
    "totalEntries" : 6
  },
  "emailAddress" : "sample@rackspace.com",
  "updated" : "2011-06-24T01:23:15.000+0000",
  "ttl" : 3600,
  "created" : "2011-06-24T01:12:51.000+0000",
  "accountId" : 1234,
  "name" : "example.com",
  "id" : 2725233,
  "comment" : "Optional domain comment..."
}]
BODY;
        return [[$body, 200]];
    }

    public static function task()
    {
        $body = <<<BODY
{
	"href": "/v1/queues/demoqueue/messages/51db6f78c508f17ddc924357?claim_id=51db7067821e727dc24df754",
	"age": 296,
	"ttl": 600,
	"body": {
		"serial": "O:35:\"RoundPartner\\\\Cloud\\\\Task\\\\Entity\\\\Task\":6:{s:8:\"taskName\";s:11:\"hello world\";s:7:\"command\";N;s:6:\"action\";N;s:9:\"arguments\";N;s:4:\"fork\";N;s:7:\"version\";N;}",
		"sha1": "d4c435d6047cd6947c83f5441dc3681d0b76bf88"
	}
}
BODY;
        return [[$body, 200]];
    }

    public static function message()
    {
        $body = <<<BODY
[
  {
	"href": "/v1/queues/demoqueue/messages/51db6f78c508f17ddc924357?claim_id=51db7067821e727dc24df754",
	"age": 296,
	"ttl": 600,
	"body": {
		"serial": "O:35:\"RoundPartner\\\\Cloud\\\\Task\\\\Entity\\\\Task\":6:{s:8:\"taskName\";s:11:\"hello world\";s:7:\"command\";N;s:6:\"action\";N;s:9:\"arguments\";N;s:4:\"fork\";N;s:7:\"version\";N;}",
		"sha1": "d4c435d6047cd6947c83f5441dc3681d0b76bf88"
	}
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
