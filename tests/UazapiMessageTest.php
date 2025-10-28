<?php

namespace UazApi\Tests;

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use UazApi\UazapiMessage;

class UazapiMessageTest extends TestCase
{
    private UazapiMessage $message;

    protected function setUp(): void
    {
        parent::setUp();
        $this->message = new UazapiMessage($this->connector);
    }

    /** @test */
    public function it_can_send_simple_text_message()
    {
        $this->mockClient->addResponse(
            MockResponse::make($this->mockMessageData(), 200)
        );

        $response = $this->message->sendText('5511999999999', 'Olá!');

        $this->assertTrue($response->successful());
        $data = $response->json();
        $this->assertEquals('text', $data['messageType']);
        $this->assertEquals('Test message', $data['text']);
    }

    /** @test */
    public function it_can_send_text_with_link_preview()
    {
        $mockData = $this->mockMessageData();
        $mockData['text'] = 'Confira: https://exemplo.com';

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->message->sendText(
            '5511999999999',
            'Confira: https://exemplo.com',
            ['linkPreview' => true]
        );

        $this->assertTrue($response->successful());
    }

    /** @test */
    public function it_can_send_text_with_custom_link_preview()
    {
        $this->mockClient->addResponse(
            MockResponse::make($this->mockMessageData(), 200)
        );

        $response = $this->message->sendText(
            '5511999999999',
            'https://exemplo.com',
            [
                'linkPreview' => true,
                'linkPreviewTitle' => 'Título Custom',
                'linkPreviewDescription' => 'Descrição custom',
                'linkPreviewImage' => 'https://exemplo.com/thumb.jpg',
                'linkPreviewLarge' => true
            ]
        );

        $this->assertTrue($response->successful());
    }

    /** @test */
    public function it_can_send_text_with_delay()
    {
        $this->mockClient->addResponse(
            MockResponse::make($this->mockMessageData(), 200)
        );

        $response = $this->message->sendText(
            '5511999999999',
            'Mensagem com delay',
            ['delay' => 2000]
        );

        $this->assertTrue($response->successful());
    }

    /** @test */
    public function it_can_send_text_with_mentions()
    {
        $this->mockClient->addResponse(
            MockResponse::make($this->mockMessageData(), 200)
        );

        $response = $this->message->sendText(
            '120363012345678901@g.us',
            'Olá @todos!',
            ['mentions' => 'all']
        );

        $this->assertTrue($response->successful());
    }

    /** @test */
    public function it_can_send_text_as_reply()
    {
        $this->mockClient->addResponse(
            MockResponse::make($this->mockMessageData(), 200)
        );

        $response = $this->message->sendText(
            '5511999999999',
            'Respondendo sua mensagem',
            ['replyid' => '3EB0538DA65A59F6D8A251']
        );

        $this->assertTrue($response->successful());
    }

    /** @test */
    public function it_can_send_image_with_url()
    {
        $mockData = $this->mockMessageData();
        $mockData['messageType'] = 'image';

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->message->sendImage(
            '5511999999999',
            'https://exemplo.com/foto.jpg'
        );

        $this->assertTrue($response->successful());
        $this->assertEquals('image', $response->json()['messageType']);
    }

    /** @test */
    public function it_can_send_image_with_caption()
    {
        $mockData = $this->mockMessageData();
        $mockData['messageType'] = 'image';
        $mockData['text'] = 'Veja esta foto!';

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->message->sendImage(
            '5511999999999',
            'https://exemplo.com/foto.jpg',
            'Veja esta foto!'
        );

        $this->assertTrue($response->successful());
        $this->assertEquals('Veja esta foto!', $response->json()['text']);
    }

    /** @test */
    public function it_can_send_video()
    {
        $mockData = $this->mockMessageData();
        $mockData['messageType'] = 'video';

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->message->sendVideo(
            '5511999999999',
            'https://exemplo.com/video.mp4',
            'Confira este vídeo!'
        );

        $this->assertTrue($response->successful());
        $this->assertEquals('video', $response->json()['messageType']);
    }

    /** @test */
    public function it_can_send_document()
    {
        $mockData = $this->mockMessageData();
        $mockData['messageType'] = 'document';

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->message->sendDocument(
            '5511999999999',
            'https://exemplo.com/doc.pdf',
            'Documento.pdf',
            'Segue o documento'
        );

        $this->assertTrue($response->successful());
        $this->assertEquals('document', $response->json()['messageType']);
    }

    /** @test */
    public function it_can_send_audio()
    {
        $mockData = $this->mockMessageData();
        $mockData['messageType'] = 'audio';

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->message->sendAudio(
            '5511999999999',
            'https://exemplo.com/audio.mp3'
        );

        $this->assertTrue($response->successful());
        $this->assertEquals('audio', $response->json()['messageType']);
    }

    /** @test */
    public function it_can_send_voice_message()
    {
        $mockData = $this->mockMessageData();
        $mockData['messageType'] = 'ptt';

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->message->sendVoice(
            '5511999999999',
            'https://exemplo.com/voice.ogg'
        );

        $this->assertTrue($response->successful());
        $this->assertEquals('ptt', $response->json()['messageType']);
    }

    /** @test */
    public function it_can_send_sticker()
    {
        $mockData = $this->mockMessageData();
        $mockData['messageType'] = 'sticker';

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->message->sendSticker(
            '5511999999999',
            'https://exemplo.com/sticker.webp'
        );

        $this->assertTrue($response->successful());
        $this->assertEquals('sticker', $response->json()['messageType']);
    }

    /** @test */
    public function it_can_send_contact()
    {
        $mockData = $this->mockMessageData();
        $mockData['messageType'] = 'vcard';

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $contacts = [
            [
                'fullName' => 'João Silva',
                'waid' => '5511888888888',
                'phoneNumber' => '+55 11 88888-8888'
            ]
        ];

        $response = $this->message->sendContact('5511999999999', $contacts);

        $this->assertTrue($response->successful());
    }

    /** @test */
    public function it_can_send_location()
    {
        $mockData = $this->mockMessageData();
        $mockData['messageType'] = 'location';

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $response = $this->message->sendLocation(
            '5511999999999',
            -23.5505199,
            -46.6333094,
            'Avenida Paulista',
            'Av. Paulista, 1578 - Bela Vista, São Paulo - SP'
        );

        $this->assertTrue($response->successful());
        $this->assertEquals('location', $response->json()['messageType']);
    }

    /** @test */
    public function it_can_send_message_with_tracking()
    {
        $this->mockClient->addResponse(
            MockResponse::make($this->mockMessageData(), 200)
        );

        $response = $this->message->sendText(
            '5511999999999',
            'Mensagem rastreada',
            [
                'track_source' => 'crm',
                'track_id' => 'msg-12345'
            ]
        );

        $this->assertTrue($response->successful());
    }

    /** @test */
    public function it_can_mark_chat_as_read()
    {
        $this->mockClient->addResponse(
            MockResponse::make($this->mockMessageData(), 200)
        );

        $response = $this->message->sendText(
            '5511999999999',
            'Teste',
            ['readchat' => true]
        );

        $this->assertTrue($response->successful());
    }

    /** @test */
    public function it_can_mark_messages_as_read()
    {
        $this->mockClient->addResponse(
            MockResponse::make($this->mockMessageData(), 200)
        );

        $response = $this->message->sendText(
            '5511999999999',
            'Teste',
            ['readmessages' => true]
        );

        $this->assertTrue($response->successful());
    }

    /** @test */
    public function it_can_send_forwarded_message()
    {
        $this->mockClient->addResponse(
            MockResponse::make($this->mockMessageData(), 200)
        );

        $response = $this->message->sendText(
            '5511999999999',
            'Mensagem encaminhada',
            ['forward' => true]
        );

        $this->assertTrue($response->successful());
    }

    /** @test */
    public function it_handles_invalid_number()
    {
        $this->mockClient->addResponse(
            MockResponse::make(['error' => 'Invalid number'], 400)
        );

        $response = $this->message->sendText('invalid', 'Test');

        $this->assertFalse($response->successful());
        $this->assertEquals(400, $response->status());
    }

    /** @test */
    public function it_handles_missing_parameters()
    {
        $this->mockClient->addResponse(
            MockResponse::make(['error' => 'Missing number or text'], 400)
        );

        $response = $this->message->sendText('', '');

        $this->assertFalse($response->successful());
    }

    /** @test */
    public function it_handles_rate_limit()
    {
        $this->mockClient->addResponse(
            MockResponse::make(['error' => 'Rate limit exceeded'], 429)
        );

        $response = $this->message->sendText('5511999999999', 'Test');

        $this->assertFalse($response->successful());
        $this->assertEquals(429, $response->status());
    }

    /** @test */
    public function it_handles_media_upload_failure()
    {
        $this->mockClient->addResponse(
            MockResponse::make(['error' => 'Failed to upload media'], 500)
        );

        $response = $this->message->sendImage(
            '5511999999999',
            'https://invalid-url.com/image.jpg'
        );

        $this->assertFalse($response->successful());
        $this->assertEquals(500, $response->status());
    }
}

