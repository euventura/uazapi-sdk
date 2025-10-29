<?php

namespace euventura\UazapiSdk\Tests;

use euventura\UazapiSdk\Resources\UazapiQuickReply;
use Saloon\Http\Faking\MockResponse;

class UazapiQuickReplyTest extends TestCase
{
    private UazapiQuickReply $quickReply;

    protected function setUp(): void
    {
        parent::setUp();
        $this->quickReply = new UazapiQuickReply($this->connector);
    }

    /** @test */
    public function it_can_get_all_quick_replies()
    {
        $this->mockClient->addResponse(
            MockResponse::make($this->mockQuickReplyData(), 200)
        );

        $data = $this->quickReply->getAll();

        $this->assertIsArray($data);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('shortCut', $data[0]);
        $this->assertEquals('saudacao', $data[0]['shortCut']);
    }

    /** @test */
    public function it_can_create_text_quick_reply()
    {
        $mockData = [
            'id' => 'rb_new123',
            'shortCut' => 'boas-vindas',
            'type' => 'text',
            'text' => 'Bem-vindo!',
            'onWhatsApp' => false,
            'created' => '2025-01-28T10:00:00.000Z',
            'updated' => '2025-01-28T10:00:00.000Z'
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $data = $this->quickReply->createText('boas-vindas', 'Bem-vindo!');

        $this->assertIsArray($data);
        $this->assertEquals('boas-vindas', $data['shortCut']);
        $this->assertEquals('Bem-vindo!', $data['text']);
    }

    /** @test */
    public function it_can_create_image_quick_reply()
    {
        $mockData = [
            'id' => 'rb_img123',
            'shortCut' => 'catalogo',
            'type' => 'image',
            'file' => 'https://exemplo.com/catalogo.jpg',
            'onWhatsApp' => false,
            'created' => '2025-01-28T10:00:00.000Z'
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $data = $this->quickReply->createMedia(
            'catalogo',
            'image',
            'https://exemplo.com/catalogo.jpg'
        );

        $this->assertIsArray($data);
        $this->assertEquals('image', $data['type']);
        $this->assertEquals('catalogo', $data['shortCut']);
    }

    /** @test */
    public function it_can_create_document_quick_reply()
    {
        $mockData = [
            'id' => 'rb_doc123',
            'shortCut' => 'tabela',
            'type' => 'document',
            'file' => 'https://exemplo.com/precos.pdf',
            'docName' => 'Tabela de Preços.pdf',
            'onWhatsApp' => false,
            'created' => '2025-01-28T10:00:00.000Z'
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $data = $this->quickReply->createMedia(
            'tabela',
            'document',
            'https://exemplo.com/precos.pdf',
            'Tabela de Preços.pdf'
        );

        $this->assertIsArray($data);
        $this->assertEquals('document', $data['type']);
        $this->assertEquals('Tabela de Preços.pdf', $data['docName']);
    }

    /** @test */
    public function it_can_update_text_quick_reply()
    {
        $mockData = [
            'id' => 'rb9da9c03637452',
            'shortCut' => 'saudacao-nova',
            'type' => 'text',
            'text' => 'Olá! Bem-vindo à nossa empresa!',
            'onWhatsApp' => false,
            'updated' => '2025-01-28T11:00:00.000Z'
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $data = $this->quickReply->updateText(
            'rb9da9c03637452',
            'saudacao-nova',
            'Olá! Bem-vindo à nossa empresa!'
        );

        $this->assertIsArray($data);
        $this->assertEquals('saudacao-nova', $data['shortCut']);
        $this->assertEquals('Olá! Bem-vindo à nossa empresa!', $data['text']);
    }

    /** @test */
    public function it_can_update_media_quick_reply()
    {
        $mockData = [
            'id' => 'rb9da9c03637452',
            'shortCut' => 'catalogo-atualizado',
            'type' => 'image',
            'file' => 'https://exemplo.com/novo-catalogo.jpg',
            'onWhatsApp' => false,
            'updated' => '2025-01-28T11:00:00.000Z'
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $data = $this->quickReply->updateMedia(
            'rb9da9c03637452',
            'catalogo-atualizado',
            'image',
            'https://exemplo.com/novo-catalogo.jpg'
        );

        $this->assertIsArray($data);
        $this->assertEquals('catalogo-atualizado', $data['shortCut']);
    }

    /** @test */
    public function it_can_delete_quick_reply()
    {
        $mockData = [
            'success' => true,
            'message' => 'Resposta rápida deletada com sucesso',
            'quickReplies' => []
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $data = $this->quickReply->delete('rb9da9c03637452');

        $this->assertIsArray($data);
        $this->assertTrue($data['success']);
    }

    /** @test */
    public function it_can_use_generic_edit_method_to_create()
    {
        $mockData = [
            'id' => 'rb_generic123',
            'shortCut' => 'despedida',
            'type' => 'text',
            'text' => 'Até logo!',
            'onWhatsApp' => false
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $data = $this->quickReply->edit(
            shortCut: 'despedida',
            type: 'text',
            text: 'Até logo!'
        );

        $this->assertIsArray($data);
        $this->assertEquals('despedida', $data['shortCut']);
    }

    /** @test */
    public function it_can_use_generic_edit_method_to_update()
    {
        $mockData = [
            'id' => 'rb9da9c03637452',
            'shortCut' => 'ola-atualizado',
            'type' => 'text',
            'text' => 'Olá! Atualizado!',
            'onWhatsApp' => false
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $data = $this->quickReply->edit(
            shortCut: 'ola-atualizado',
            type: 'text',
            id: 'rb9da9c03637452',
            text: 'Olá! Atualizado!'
        );

        $this->assertIsArray($data);
    }

    /** @test */
    public function it_handles_duplicate_shortcut()
    {
        $this->mockClient->addResponse(
            MockResponse::make(['error' => 'Shortcut already exists'], 400)
        );

        $data = $this->quickReply->createText('saudacao', 'Teste');

        $this->assertArrayHasKey('error', $data);
    }

    /** @test */
    public function it_handles_quick_reply_not_found()
    {
        $this->mockClient->addResponse(
            MockResponse::make(['error' => 'Template not found'], 404)
        );

        $data = $this->quickReply->updateText(
            'nonexistent-id',
            'test',
            'Test'
        );

        $this->assertArrayHasKey('error', $data);
    }

    /** @test */
    public function it_cannot_modify_whatsapp_originated_replies()
    {
        $this->mockClient->addResponse(
            MockResponse::make([
                'error' => 'Não é possível modificar template originado do WhatsApp'
            ], 403)
        );

        $data = $this->quickReply->updateText(
            'whatsapp-id',
            'test',
            'Test'
        );

        $this->assertArrayHasKey('error', $data);
    }

    /** @test */
    public function it_validates_required_fields_for_text()
    {
        $this->mockClient->addResponse(
            MockResponse::make(['error' => 'Text is required for type text'], 400)
        );

        $data = $this->quickReply->edit(
            shortCut: 'test',
            type: 'text'
        // Missing text parameter
        );

        $this->assertArrayHasKey('error', $data);
    }

    /** @test */
    public function it_validates_required_fields_for_media()
    {
        $this->mockClient->addResponse(
            MockResponse::make(['error' => 'File is required for media types'], 400)
        );

        $data = $this->quickReply->edit(
            shortCut: 'test',
            type: 'image'
        // Missing file parameter
        );

        $this->assertArrayHasKey('error', $data);
    }

    /** @test */
    public function it_supports_all_media_types()
    {
        $mediaTypes = ['image', 'video', 'audio', 'myaudio', 'ptt', 'document'];

        foreach ($mediaTypes as $type) {
            $mockData = [
                'id' => 'rb_' . $type,
                'shortCut' => 'test-' . $type,
                'type' => $type,
                'file' => 'https://exemplo.com/file.' . $type,
                'onWhatsApp' => false
            ];

            $this->mockClient->addResponse(
                MockResponse::make($mockData, 200)
            );

            $data = $this->quickReply->createMedia(
                'test-' . $type,
                $type,
                'https://exemplo.com/file.' . $type
            );

            $this->assertIsArray($data);
            $this->assertEquals($type, $data['type']);
        }
    }
}

