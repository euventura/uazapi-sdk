<?php

namespace euventura\UazapiSdk\Tests\Requests;

use euventura\UazapiSdk\Requests\Message\SendContactRequest;
use euventura\UazapiSdk\Requests\Message\SendLocationRequest;
use euventura\UazapiSdk\Requests\Message\SendMediaRequest;
use euventura\UazapiSdk\Requests\Message\SendTextRequest;
use PHPUnit\Framework\TestCase;

class MessageRequestsTest extends TestCase
{
    public function test_send_text_request_body_basic()
    {
        $req = new SendTextRequest('5511999999999', 'Olá mundo');

        $this->assertEquals('/send/text', $req->resolveEndpoint());

        $body = $req->defaultBody();

        $this->assertArrayHasKey('number', $body);
        $this->assertArrayHasKey('text', $body);
        $this->assertEquals('Olá mundo', $body['text']);
    }

    public function test_send_text_request_with_optional_fields()
    {
        $req = new SendTextRequest(
            '5511999999999',
            'Link',
            true,
            'Titulo',
            'Desc',
            'https://img',
            true,
            'reply123',
            'all',
            true,
            true,
            1000,
            true,
            'crm',
            'msg-1'
        );

        $body = $req->defaultBody();

        $this->assertTrue($body['linkPreview']);
        $this->assertEquals('Titulo', $body['linkPreviewTitle']);
        $this->assertEquals('Desc', $body['linkPreviewDescription']);
        $this->assertEquals('https://img', $body['linkPreviewImage']);
        $this->assertEquals('reply123', $body['replyid']);
        $this->assertEquals('all', $body['mentions']);
        $this->assertEquals(1000, $body['delay']);
        $this->assertEquals('crm', $body['track_source']);
        $this->assertEquals('msg-1', $body['track_id']);
    }

    public function test_send_media_request_body()
    {
        $req = new SendMediaRequest('5511999999999', 'image', 'https://exemplo.com/photo.jpg', 'Legenda');

        $this->assertEquals('/send/media', $req->resolveEndpoint());

        $body = $req->defaultBody();

        $this->assertEquals('image', $body['type']);
        $this->assertEquals('https://exemplo.com/photo.jpg', $body['file']);
        $this->assertEquals('Legenda', $body['text']);
    }

    public function test_send_location_request_body()
    {
        $req = new SendLocationRequest('5511999999999', -23.55, -46.63, 'Local', 'Endereço');

        $this->assertEquals('/send/location', $req->resolveEndpoint());

        $body = $req->defaultBody();

        $this->assertEquals(-23.55, $body['latitude']);
        $this->assertEquals(-46.63, $body['longitude']);
        $this->assertEquals('Local', $body['name']);
        $this->assertEquals('Endereço', $body['address']);
    }

    public function test_send_contact_request_body()
    {
        $contacts = [
            [
                'fullName' => 'João',
                'waid' => '5511888888888',
                'phoneNumber' => '+55 11 88888-8888'
            ]
        ];

        $req = new SendContactRequest('5511999999999', $contacts);

        $this->assertEquals('/send/contact', $req->resolveEndpoint());

        $body = $req->defaultBody();

        $this->assertEquals($contacts, $body['contacts']);
    }
}

