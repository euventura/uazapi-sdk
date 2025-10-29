<?php

namespace euventura\UazapiSdk\Tests;

use euventura\UazapiSdk\UazapiApiConnector;

class UazapiApiConnectorTest extends TestCase
{
    /** @test */
    public function it_initializes_with_server_and_token()
    {
        $connector = new UazapiApiConnector('https://test.uazapi.com', 'test-token');

        $this->assertInstanceOf(UazapiApiConnector::class, $connector);
    }

    /** @test */
    public function it_resolves_base_url_correctly()
    {
        $connector = new UazapiApiConnector('https://custom.uazapi.com', 'test-token');

        $this->assertEquals('https://custom.uazapi.com', $connector->resolveBaseUrl());
    }

    /** @test */
    public function it_sets_default_headers_with_instance_token()
    {
        $connector = new UazapiApiConnector('https://test.uazapi.com', 'instance-token-123');

        $headers = $connector->defaultHeaders();

        $this->assertArrayHasKey('token', $headers);
        $this->assertEquals('instance-token-123', $headers['token']);
        $this->assertEquals('application/json', $headers['Content-Type']);
        $this->assertEquals('application/json', $headers['Accept']);
    }

    /** @test */
    public function it_sets_default_headers_with_admin_token()
    {
        $connector = new UazapiApiConnector(
            'https://test.uazapi.com',
            'admin-token-123',
            isAdmin: true
        );

        $headers = $connector->defaultHeaders();

        $this->assertArrayHasKey('admintoken', $headers);
        $this->assertEquals('admin-token-123', $headers['admintoken']);
    }

    /** @test */
    public function it_uses_instance_token_by_default()
    {
        $connector = new UazapiApiConnector('https://test.uazapi.com', 'test-token');

        $headers = $connector->defaultHeaders();

        $this->assertArrayHasKey('token', $headers);
        $this->assertArrayNotHasKey('admintoken', $headers);
    }

    /** @test */
    public function it_accepts_different_server_urls()
    {
        $servers = [
            'https://free.uazapi.com',
            'https://pro.uazapi.com',
            'https://custom-domain.com',
            'http://localhost:3000'
        ];

        foreach ($servers as $server) {
            $connector = new UazapiApiConnector($server, 'test-token');
            $this->assertEquals($server, $connector->resolveBaseUrl());
        }
    }

    /** @test */
    public function it_maintains_json_content_type()
    {
        $connector = new UazapiApiConnector('https://test.uazapi.com', 'test-token');

        $headers = $connector->defaultHeaders();

        $this->assertEquals('application/json', $headers['Content-Type']);
        $this->assertEquals('application/json', $headers['Accept']);
    }
}

