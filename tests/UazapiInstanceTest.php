<?php

namespace UazApi\Tests;

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use UazApi\UazapiInstance;

class UazapiInstanceTest extends TestCase
{
    private UazapiInstance $instance;

    protected function setUp(): void
    {
        parent::setUp();
        $this->instance = new UazapiInstance($this->connector);
    }

    /** @test */
    public function it_can_connect_without_phone()
    {
        $mockData = array_merge($this->mockInstanceData(), [
            'qrcode' => 'data:image/png;base64,iVBORw0KGgo...',
            'connected' => false,
            'loggedIn' => false
        ]);

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->instance->connect();

        $this->assertTrue($response->successful());
    }

    /** @test */
    public function it_can_connect_with_phone()
    {
        $mockData = array_merge($this->mockInstanceData(), [
            'paircode' => '1234-5678',
            'connected' => false,
            'loggedIn' => false
        ]);

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->instance->connect('5511999999999');

        $this->assertTrue($response->successful());
    }

    /** @test */
    public function it_can_disconnect()
    {
        $mockData = [
            'response' => 'Disconnected',
            'info' => 'Instance disconnected successfully',
            'instance' => $this->mockInstanceData()['instance']
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->instance->disconnect();

        $this->assertTrue($response->successful());
        $this->assertEquals('Disconnected', $response->json()['response']);
    }

    /** @test */
    public function it_can_get_status()
    {
        $this->mockClient->addResponse(
            MockResponse::make($this->mockInstanceData(), 200)
        );

        $response = $this->instance->status();

        $this->assertTrue($response->successful());
        $data = $response->json();
        $this->assertArrayHasKey('instance', $data);
        $this->assertArrayHasKey('status', $data);
        $this->assertTrue($data['status']['connected']);
    }

    /** @test */
    public function it_can_delete_instance()
    {
        $mockData = [
            'response' => 'Instance Deleted',
            'info' => 'Instance removed from database'
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->instance->delete();

        $this->assertTrue($response->successful());
        $this->assertEquals('Instance Deleted', $response->json()['response']);
    }

    /** @test */
    public function it_can_update_instance_name()
    {
        $mockData = $this->mockInstanceData();
        $mockData['instance']['name'] = 'Updated Instance Name';

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->instance->updateName('Updated Instance Name');

        $this->assertTrue($response->successful());
        $this->assertEquals('Updated Instance Name', $response->json()['instance']['name']);
    }

    /** @test */
    public function it_can_get_privacy_settings()
    {
        $mockData = [
            'groupadd' => 'contacts',
            'last' => 'contacts',
            'status' => 'contacts',
            'profile' => 'contacts',
            'readreceipts' => 'all',
            'online' => 'all',
            'calladd' => 'all'
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->instance->getPrivacy();

        $this->assertTrue($response->successful());
        $data = $response->json();
        $this->assertEquals('contacts', $data['groupadd']);
        $this->assertEquals('all', $data['readreceipts']);
    }

    /** @test */
    public function it_can_update_privacy_settings()
    {
        $privacySettings = [
            'groupadd' => 'none',
            'last' => 'none',
            'status' => 'contacts'
        ];

        $this->mockClient->addResponse(
            MockResponse::make($privacySettings, 200)
        );

        $response = $this->instance->updatePrivacy($privacySettings);

        $this->assertTrue($response->successful());
        $data = $response->json();
        $this->assertEquals('none', $data['groupadd']);
        $this->assertEquals('contacts', $data['status']);
    }

    /** @test */
    public function it_handles_connection_errors()
    {
        $this->mockClient->addResponse(
            MockResponse::make(['error' => 'Connection failed'], 500)
        );

        $response = $this->instance->connect();

        $this->assertFalse($response->successful());
        $this->assertEquals(500, $response->status());
    }

    /** @test */
    public function it_handles_authentication_errors()
    {
        $this->mockClient->addResponse(
            MockResponse::make(['error' => 'Invalid token'], 401)
        );

        $response = $this->instance->status();

        $this->assertFalse($response->successful());
        $this->assertEquals(401, $response->status());
        $this->assertEquals('Invalid token', $response->json()['error']);
    }
}

