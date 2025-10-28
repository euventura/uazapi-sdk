<?php

namespace UazApi\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use UazApi\UazapiApiConnector;

/**
 * Base Test Case for UazAPI SDK Tests
 *
 * Provides helper methods and common setup for all test cases
 */
abstract class TestCase extends BaseTestCase
{
    protected UazapiApiConnector $connector;
    protected MockClient $mockClient;

    /**
     * Setup test environment
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create connector with test credentials
        $this->connector = new UazapiApiConnector(
            'https://test.uazapi.com',
            'test-token-123'
        );

        // Create mock client for API responses
        $this->mockClient = new MockClient();

        // Apply mock client to connector
        $this->connector->withMockClient($this->mockClient);
    }

    /**
     * Create a mock successful response
     *
     * @param array $data Response data
     * @param int $status HTTP status code
     * @return MockResponse
     */
    protected function mockSuccessResponse(array $data = [], int $status = 200): MockResponse
    {
        return MockResponse::make($data, $status);
    }

    /**
     * Create a mock error response
     *
     * @param string $error Error message
     * @param int $status HTTP status code
     * @return MockResponse
     */
    protected function mockErrorResponse(string $error = 'Error', int $status = 400): MockResponse
    {
        return MockResponse::make(['error' => $error], $status);
    }

    /**
     * Create a mock instance response
     *
     * @return array
     */
    protected function mockInstanceData(): array
    {
        return [
            'instance' => [
                'id' => 'r183e2ef9597845',
                'token' => 'test-token-123',
                'status' => 'connected',
                'name' => 'Test Instance',
                'profileName' => 'Test Profile',
                'profilePicUrl' => 'https://example.com/profile.jpg',
                'isBusiness' => false,
                'plataform' => 'Android',
                'systemName' => 'uazapi',
                'owner' => 'test@example.com',
                'created' => '2025-01-28T10:00:00.000Z',
                'updated' => '2025-01-28T10:00:00.000Z'
            ],
            'status' => [
                'connected' => true,
                'loggedIn' => true,
                'jid' => [
                    'user' => '5511999999999',
                    'agent' => 0,
                    'device' => 0,
                    'server' => 's.whatsapp.net'
                ]
            ]
        ];
    }

    /**
     * Create a mock message response
     *
     * @return array
     */
    protected function mockMessageData(): array
    {
        return [
            'id' => 'r9a8b7c6d5',
            'messageid' => '3EB0538DA65A59F6D8A251',
            'chatid' => 'chat-123',
            'fromMe' => true,
            'isGroup' => false,
            'messageType' => 'text',
            'messageTimestamp' => time(),
            'text' => 'Test message',
            'status' => 'sent',
            'created' => '2025-01-28T10:00:00.000Z',
            'response' => [
                'status' => 'success',
                'message' => 'Message sent successfully'
            ]
        ];
    }

    /**
     * Create a mock webhook response
     *
     * @return array
     */
    protected function mockWebhookData(): array
    {
        return [
            [
                'id' => 'wh_123456',
                'instance_id' => 'inst_123',
                'enabled' => true,
                'url' => 'https://example.com/webhook',
                'events' => ['messages', 'connection'],
                'AddUrlTypesMessages' => false,
                'addUrlEvents' => false,
                'excludeMessages' => ['wasSentByApi'],
                'created' => '2025-01-28T10:00:00.000Z',
                'updated' => '2025-01-28T10:00:00.000Z'
            ]
        ];
    }

    /**
     * Create a mock quick reply response
     *
     * @return array
     */
    protected function mockQuickReplyData(): array
    {
        return [
            [
                'id' => 'rb9da9c03637452',
                'shortCut' => 'saudacao',
                'type' => 'text',
                'text' => 'Olá! Como posso ajudar?',
                'onWhatsApp' => false,
                'created' => '2025-01-28T10:00:00.000Z',
                'updated' => '2025-01-28T10:00:00.000Z'
            ]
        ];
    }
}

