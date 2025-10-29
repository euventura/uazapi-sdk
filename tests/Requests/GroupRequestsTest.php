<?php

namespace euventura\UazapiSdk\Tests\Requests;

use euventura\UazapiSdk\Requests\Group\CreateGroupRequest;
use euventura\UazapiSdk\Requests\Group\GetGroupInfoRequest;
use euventura\UazapiSdk\Requests\Group\GetInviteLinkRequest;
use euventura\UazapiSdk\Requests\Group\JoinGroupRequest;
use euventura\UazapiSdk\Requests\Group\ListGroupsRequest;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class GroupRequestsTest extends TestCase
{
    public function test_create_group_request_body()
    {
        $req = new CreateGroupRequest('Meu Grupo', ['5511999999999', '5511888888888']);

        $this->assertEquals('/group/create', $req->resolveEndpoint());

        $body = $req->defaultBody();

        $this->assertArrayHasKey('name', $body);
        $this->assertArrayHasKey('participants', $body);
        $this->assertEquals('Meu Grupo', $body['name']);
        $this->assertCount(2, $body['participants']);
    }

    public function test_join_group_request_body()
    {
        $req = new JoinGroupRequest('https://chat.whatsapp.com/AAAA');

        $this->assertEquals('/group/join', $req->resolveEndpoint());

        $body = $req->defaultBody();

        $this->assertArrayHasKey('inviteCode', $body);
        $this->assertEquals('https://chat.whatsapp.com/AAAA', $body['inviteCode']);
    }

    public function test_list_groups_request_query()
    {
        $req = new ListGroupsRequest(true, false);

        $this->assertEquals('/group/list', $req->resolveEndpoint());

        // defaultQuery is protected; use reflection to invoke
        $ref = new ReflectionMethod($req, 'defaultQuery');
        $ref->setAccessible(true);
        $query = $ref->invoke($req);

        $this->assertArrayHasKey('force', $query);
        $this->assertArrayHasKey('noparticipants', $query);
        $this->assertEquals('true', $query['force']);
        $this->assertEquals('false', $query['noparticipants']);
    }

    public function test_get_invite_link_endpoint()
    {
        $req = new GetInviteLinkRequest('120363153742561022@g.us');

        $this->assertStringContainsString('120363153742561022@g.us', $req->resolveEndpoint());
        $this->assertEquals('/group/invitelink/120363153742561022@g.us', $req->resolveEndpoint());
    }

    public function test_get_group_info_body_with_options()
    {
        $req = new GetGroupInfoRequest('120363153742561022@g.us', true, true, true);

        $this->assertEquals('/group/info', $req->resolveEndpoint());

        $body = $req->defaultBody();

        $this->assertArrayHasKey('groupjid', $body);
        $this->assertArrayHasKey('getInviteLink', $body);
        $this->assertArrayHasKey('getRequestsParticipants', $body);
        $this->assertArrayHasKey('force', $body);

        $this->assertTrue($body['getInviteLink']);
        $this->assertTrue($body['getRequestsParticipants']);
        $this->assertTrue($body['force']);
    }
}

