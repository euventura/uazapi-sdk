<?php

namespace euventura\UazapiSdk\Tests;

use euventura\UazapiSdk\Resources\UazapiInstance;
use euventura\UazapiSdk\Resources\UazapiMessage;
use euventura\UazapiSdk\Resources\UazapiProfile;
use euventura\UazapiSdk\Resources\UazapiQuickReply;
use euventura\UazapiSdk\Resources\UazapiWebhook;
use euventura\UazapiSdk\Uazapi;

class UazapiTest extends TestCase
{
    private Uazapi $uazapi;

    protected function setUp(): void
    {
        parent::setUp();
        $this->uazapi = new Uazapi('https://test.uazapi.com', 'test-token-123');
    }

    /** @test */
    public function it_initializes_with_server_and_token()
    {
        $this->assertInstanceOf(Uazapi::class, $this->uazapi);
        $this->assertNotNull($this->uazapi->connector);
    }

    /** @test */
    public function it_returns_instance_resource()
    {
        $instance = $this->uazapi->instance();

        $this->assertInstanceOf(UazapiInstance::class, $instance);
    }

    /** @test */
    public function it_returns_message_resource()
    {
        $message = $this->uazapi->message();

        $this->assertInstanceOf(UazapiMessage::class, $message);
    }

    /** @test */
    public function it_returns_profile_resource()
    {
        $profile = $this->uazapi->profile();

        $this->assertInstanceOf(UazapiProfile::class, $profile);
    }

    /** @test */
    public function it_returns_quick_reply_resource()
    {
        $quickReply = $this->uazapi->quickReply();

        $this->assertInstanceOf(UazapiQuickReply::class, $quickReply);
    }

    /** @test */
    public function it_returns_webhook_resource()
    {
        $webhook = $this->uazapi->webhook();

        $this->assertInstanceOf(UazapiWebhook::class, $webhook);
    }

    /** @test */
    public function it_can_be_used_in_fluent_api()
    {
        // This should not throw any errors
        $instance = $this->uazapi->instance();
        $message = $this->uazapi->message();
        $profile = $this->uazapi->profile();

        $this->assertInstanceOf(UazapiInstance::class, $instance);
        $this->assertInstanceOf(UazapiMessage::class, $message);
        $this->assertInstanceOf(UazapiProfile::class, $profile);
    }
}

