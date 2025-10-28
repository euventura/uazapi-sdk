<?php

namespace UazApi\Tests;

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use UazApi\UazapiWebhook;

class UazapiWebhookTest extends TestCase
{
    private UazapiWebhook $webhook;

    protected function setUp(): void
    {
        parent::setUp();
        $this->webhook = new UazapiWebhook($this->connector);
    }

    /** @test */
    public function it_can_get_webhook_configuration()
    {
        $this->mockClient->addResponse(
            MockResponse::make($this->mockWebhookData(), 200)
        );

        $response = $this->webhook->get();

        $this->assertTrue($response->successful());
        $data = $response->json();
        $this->assertIsArray($data);
        $this->assertArrayHasKey('url', $data[0]);
        $this->assertEquals('https://example.com/webhook', $data[0]['url']);
    }

    /** @test */
    public function it_can_configure_webhook_simple_mode()
    {
        $mockData = [
            'id' => 'wh_new123',
            'enabled' => true,
            'url' => 'https://mysite.com/webhook',
            'events' => ['messages', 'connection'],
            'excludeMessages' => ['wasSentByApi'],
            'addUrlEvents' => false,
            'AddUrlTypesMessages' => false
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->webhook->configure(
            'https://mysite.com/webhook',
            ['messages', 'connection'],
            excludeMessages: ['wasSentByApi']
        );

        $this->assertTrue($response->successful());
        $data = $response->json();
        $this->assertEquals('https://mysite.com/webhook', $data['url']);
        $this->assertContains('wasSentByApi', $data['excludeMessages']);
    }

    /** @test */
    public function it_can_configure_webhook_with_url_events()
    {
        $mockData = [
            'id' => 'wh_456',
            'enabled' => true,
            'url' => 'https://mysite.com/webhook',
            'events' => ['messages'],
            'addUrlEvents' => true,
            'AddUrlTypesMessages' => true,
            'excludeMessages' => []
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->webhook->configure(
            'https://mysite.com/webhook',
            ['messages'],
            addUrlEvents: true,
            addUrlTypesMessages: true
        );

        $this->assertTrue($response->successful());
        $data = $response->json();
        $this->assertTrue($data['addUrlEvents']);
        $this->assertTrue($data['AddUrlTypesMessages']);
    }

    /** @test */
    public function it_can_add_new_webhook_advanced_mode()
    {
        $mockData = [
            'id' => 'wh_new789',
            'enabled' => true,
            'url' => 'https://another-site.com/webhook',
            'events' => ['presence', 'groups'],
            'excludeMessages' => []
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->webhook->add(
            'https://another-site.com/webhook',
            ['presence', 'groups']
        );

        $this->assertTrue($response->successful());
        $data = $response->json();
        $this->assertArrayHasKey('id', $data);
        $this->assertEquals('wh_new789', $data['id']);
    }

    /** @test */
    public function it_can_update_existing_webhook()
    {
        $mockData = [
            'id' => 'wh_123',
            'enabled' => true,
            'url' => 'https://updated-site.com/webhook',
            'events' => ['messages', 'connection', 'presence'],
            'excludeMessages' => ['wasSentByApi', 'isGroupYes']
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->webhook->update(
            'wh_123',
            'https://updated-site.com/webhook',
            ['messages', 'connection', 'presence'],
            excludeMessages: ['wasSentByApi', 'isGroupYes']
        );

        $this->assertTrue($response->successful());
        $data = $response->json();
        $this->assertEquals('https://updated-site.com/webhook', $data['url']);
        $this->assertCount(3, $data['events']);
    }

    /** @test */
    public function it_can_delete_webhook()
    {
        $mockData = [
            'success' => true,
            'message' => 'Webhook deletado com sucesso'
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->webhook->delete('wh_123');

        $this->assertTrue($response->successful());
        $this->assertTrue($response->json()['success']);
    }

    /** @test */
    public function it_can_disable_webhook()
    {
        $mockData = [
            'id' => 'wh_123',
            'enabled' => false,
            'url' => 'https://mysite.com/webhook',
            'events' => ['messages']
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->webhook->configure(
            'https://mysite.com/webhook',
            ['messages'],
            enabled: false
        );

        $this->assertTrue($response->successful());
        $this->assertFalse($response->json()['enabled']);
    }

    /** @test */
    public function it_validates_webhook_url()
    {
        $this->mockClient->addResponse(
            MockResponse::make(['error' => 'Invalid URL'], 400)
        );

        $response = $this->webhook->configure('invalid-url', ['messages']);

        $this->assertFalse($response->successful());
        $this->assertEquals(400, $response->status());
    }

    /** @test */
    public function it_validates_webhook_events()
    {
        $this->mockClient->addResponse(
            MockResponse::make(['error' => 'Invalid events'], 400)
        );

        $response = $this->webhook->configure(
            'https://mysite.com/webhook',
            [] // Empty events array
        );

        $this->assertFalse($response->successful());
        $this->assertEquals(400, $response->status());
    }

    /** @test */
    public function it_handles_webhook_not_found()
    {
        $this->mockClient->addResponse(
            MockResponse::make(['error' => 'Webhook not found'], 404)
        );

        $response = $this->webhook->update(
            'wh_nonexistent',
            'https://mysite.com/webhook',
            ['messages']
        );

        $this->assertFalse($response->successful());
        $this->assertEquals(404, $response->status());
    }

    /** @test */
    public function it_supports_all_event_types()
    {
        $allEvents = [
            'connection',
            'history',
            'messages',
            'messages_update',
            'call',
            'contacts',
            'presence',
            'groups',
            'labels',
            'chats',
            'chat_labels',
            'blocks',
            'leads'
        ];

        $mockData = [
            'id' => 'wh_all',
            'enabled' => true,
            'url' => 'https://mysite.com/webhook',
            'events' => $allEvents
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->webhook->configure(
            'https://mysite.com/webhook',
            $allEvents
        );

        $this->assertTrue($response->successful());
        $this->assertCount(13, $response->json()['events']);
    }
}

