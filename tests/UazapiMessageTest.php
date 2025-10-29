<?php

namespace euventura\UazapiSdk\Tests;

use euventura\UazapiSdk\Resources\UazapiMessage;
use Saloon\Http\Faking\MockResponse;

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

        $data = $this->message->sendText('5511999999999', 'Olá!');

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

        $data = $this->message->sendText(
            '5511999999999',
            'Confira: https://exemplo.com',
            linkPreview: true
        );

        $this->assertIsArray($data);
    }

    /** @test */
    public function it_can_send_text_with_custom_link_preview()
    {
        $this->mockClient->addResponse(
            MockResponse::make($this->mockMessageData(), 200)
        );

        $data = $this->message->sendText(
            '5511999999999',
            'https://exemplo.com',
            linkPreview: true,
            linkPreviewTitle: 'Título Custom',
            linkPreviewDescription: 'Descrição custom',
            linkPreviewImage: 'https://exemplo.com/thumb.jpg',
            linkPreviewLarge: true
        );

        $this->assertIsArray($data);
    }

    /** @test */
    public function it_can_send_text_with_delay()
    {
        $this->mockClient->addResponse(
            MockResponse::make($this->mockMessageData(), 200)
        );

        $data = $this->message->sendText(
            '5511999999999',
            'Mensagem com delay',
            messageDelay: 2000
        );

        $this->assertIsArray($data);
    }

    /** @test */
    public function it_can_send_text_with_mentions()
    {
        $this->mockClient->addResponse(
            MockResponse::make($this->mockMessageData(), 200)
        );

        $data = $this->message->sendText(
            '120363012345678901@g.us',
            'Olá @todos!',
            mentions: 'all'
        );

        $this->assertIsArray($data);
    }

    /** @test */
    public function it_can_send_text_as_reply()
    {
        $this->mockClient->addResponse(
            MockResponse::make($this->mockMessageData(), 200)
        );

        $data = $this->message->sendText(
            '5511999999999',
            'Respondendo sua mensagem',
            replyid: '3EB0538DA65A59F6D8A251'
        );

        $this->assertIsArray($data);
    }

    /** @test */
    public function it_can_send_image_with_url()
    {
        $mockData = $this->mockMessageData();
        $mockData['messageType'] = 'image';

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $data = $this->message->sendImage(
            '5511999999999',
            'https://exemplo.com/foto.jpg'
        );

        $this->assertEquals('image', $data['messageType']);
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

        $data = $this->message->sendImage(
            '5511999999999',
            'https://exemplo.com/foto.jpg',
            'Veja esta foto!'
        );

        $this->assertEquals('Veja esta foto!', $data['text']);
    }

    /** @test */
    public function it_can_send_video()
    {
        $mockData = $this->mockMessageData();
        $mockData['messageType'] = 'video';

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $data = $this->message->sendVideo(
            '5511999999999',
            'https://exemplo.com/video.mp4',
            'Confira este vídeo!'
        );

        $this->assertEquals('video', $data['messageType']);
    }

    /** @test */
    public function it_can_send_document()
    {
        $mockData = $this->mockMessageData();
        $mockData['messageType'] = 'document';

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $data = $this->message->sendDocument(
            '5511999999999',
            'https://exemplo.com/doc.pdf',
            'Documento.pdf',
            'Segue o documento'
        );

        $this->assertEquals('document', $data['messageType']);
    }

    /** @test */
    public function it_can_send_audio()
    {
        $mockData = $this->mockMessageData();
        $mockData['messageType'] = 'audio';

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $data = $this->message->sendAudio(
            '5511999999999',
            'https://exemplo.com/audio.mp3'
        );

        $this->assertEquals('audio', $data['messageType']);
    }

    /** @test */
    public function it_can_send_voice_message()
    {
        $mockData = $this->mockMessageData();
        $mockData['messageType'] = 'ptt';

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $data = $this->message->sendVoice(
            '5511999999999',
            'https://exemplo.com/voice.ogg'
        );

        $this->assertEquals('ptt', $data['messageType']);
    }

    /** @test */
    public function it_can_send_sticker()
    {
        $mockData = $this->mockMessageData();
        $mockData['messageType'] = 'sticker';

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $data = $this->message->sendSticker(
            '5511999999999',
            'https://exemplo.com/sticker.webp'
        );

        $this->assertEquals('sticker', $data['messageType']);
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

        $data = $this->message->sendContact('5511999999999', $contacts);

        $this->assertIsArray($data);
    }

    /** @test */
    public function it_can_send_location()
    {
        $mockData = $this->mockMessageData();
        $mockData['messageType'] = 'location';

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $data = $this->message->sendLocation(
            '5511999999999',
            -23.5505199,
            -46.6333094,
            'Avenida Paulista',
            'Av. Paulista, 1578 - Bela Vista, São Paulo - SP'
        );

        $this->assertEquals('location', $data['messageType']);
    }

    /** @test */
    public function it_can_send_message_with_tracking()
    {
        $this->mockClient->addResponse(
            MockResponse::make($this->mockMessageData(), 200)
        );

        $data = $this->message->sendText(
            '5511999999999',
            'Mensagem rastreada',
            track_source: 'crm',
            track_id: 'msg-12345'
        );

        $this->assertIsArray($data);
    }

    /** @test */
    public function it_can_mark_chat_as_read()
    {
        $this->mockClient->addResponse(
            MockResponse::make($this->mockMessageData(), 200)
        );

        $data = $this->message->sendText(
            '5511999999999',
            'Teste',
            readchat: true
        );

        $this->assertIsArray($data);
    }

    /** @test */
    public function it_can_mark_messages_as_read()
    {
        $this->mockClient->addResponse(
            MockResponse::make($this->mockMessageData(), 200)
        );

        $data = $this->message->sendText(
            '5511999999999',
            'Teste',
            readmessages: true
        );

        $this->assertIsArray($data);
    }

    /** @test */
    public function it_can_send_forwarded_message()
    {
        $this->mockClient->addResponse(
            MockResponse::make($this->mockMessageData(), 200)
        );

        $data = $this->message->sendText(
            '5511999999999',
            'Mensagem encaminhada',
            forward: true
        );

        $this->assertIsArray($data);
    }

    /** @test */
    public function it_handles_invalid_number()
    {
        $this->mockClient->addResponse(
            MockResponse::make(['error' => 'Invalid number'], 400)
        );

        $data = $this->message->sendText('invalid', 'Test');

        $this->assertArrayHasKey('error', $data);
    }

    /** @test */
    public function it_handles_missing_parameters()
    {
        $this->mockClient->addResponse(
            MockResponse::make(['error' => 'Missing number or text'], 400)
        );

        $data = $this->message->sendText('', '');

        $this->assertArrayHasKey('error', $data);
    }

    /** @test */
    public function it_handles_rate_limit()
    {
        $this->mockClient->addResponse(
            MockResponse::make(['error' => 'Rate limit exceeded'], 429)
        );

        $data = $this->message->sendText('5511999999999', 'Test');

        $this->assertArrayHasKey('error', $data);
    }

    /** @test */
    public function it_handles_media_upload_failure()
    {
        $this->mockClient->addResponse(
            MockResponse::make(['error' => 'Failed to upload media'], 500)
        );

        $data = $this->message->sendImage(
            '5511999999999',
            'https://invalid-url.com/image.jpg'
        );

        $this->assertArrayHasKey('error', $data);
    }
}

