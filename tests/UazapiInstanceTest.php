<?php

namespace euventura\UazapiSdk\Tests;

use Saloon\Http\Faking\MockResponse;
use euventura\UazapiSdk\UazapiInstance;

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

        $data = $this->instance->connect();

        $this->assertIsArray($data);
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

        $data = $this->instance->connect('5511999999999');

        $this->assertIsArray($data);
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

        $data = $this->instance->disconnect();

        $this->assertIsArray($data);
        $this->assertEquals('Disconnected', $data['response']);
    }

    /** @test */
    public function it_can_get_status()
    {
        $this->mockClient->addResponse(
            MockResponse::make($this->mockInstanceData(), 200)
        );

        $data = $this->instance->status();

        $this->assertIsArray($data);
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

        $data = $this->instance->delete();

        $this->assertIsArray($data);
        $this->assertEquals('Instance Deleted', $data['response']);
    }

    /** @test */
    public function it_can_update_instance_name()
    {
        $mockData = $this->mockInstanceData();
        $mockData['instance']['name'] = 'Updated Instance Name';

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $data = $this->instance->updateName('Updated Instance Name');

        $this->assertIsArray($data);
        $this->assertEquals('Updated Instance Name', $data['instance']['name']);
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

        $data = $this->instance->getPrivacy();

        $this->assertIsArray($data);
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

        $data = $this->instance->updatePrivacy($privacySettings);

        $this->assertIsArray($data);
        $this->assertEquals('none', $data['groupadd']);
        $this->assertEquals('contacts', $data['status']);
    }

    /** @test */
    public function it_handles_connection_errors()
    {
        $this->mockClient->addResponse(
            MockResponse::make(['error' => 'Connection failed'], 500)
        );

        $data = $this->instance->connect();

        $this->assertArrayHasKey('error', $data);
    }

    /** @test */
    public function it_handles_authentication_errors()
    {
        $this->mockClient->addResponse(
            MockResponse::make(['error' => 'Invalid token'], 401)
        );

        $data = $this->instance->status();

        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Invalid token', $data['error']);
    }
}

