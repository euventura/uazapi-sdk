<?php

namespace euventura\UazapiSdk\Tests\Requests;

use euventura\UazapiSdk\Requests\Community\CreateCommunityRequest;
use euventura\UazapiSdk\Requests\Community\EditCommunityGroupsRequest;
use PHPUnit\Framework\TestCase;

class CommunityRequestsTest extends TestCase
{
    public function test_create_community_request_body()
    {
        $req = new CreateCommunityRequest('Minha Comunidade');

        $this->assertEquals('/community/create', $req->resolveEndpoint());

        $body = $req->defaultBody();

        $this->assertArrayHasKey('name', $body);
        $this->assertEquals('Minha Comunidade', $body['name']);
    }

    public function test_edit_community_groups_body()
    {
        $req = new EditCommunityGroupsRequest('120363153742561022@g.us', 'add', ['120363@g.us']);

        $this->assertEquals('/community/editgroups', $req->resolveEndpoint());

        $body = $req->defaultBody();

        $this->assertArrayHasKey('community', $body);
        $this->assertArrayHasKey('action', $body);
        $this->assertArrayHasKey('groupjids', $body);

        $this->assertEquals('add', $body['action']);
        $this->assertEquals(['120363@g.us'], $body['groupjids']);
    }
}

