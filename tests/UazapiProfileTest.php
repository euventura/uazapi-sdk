<?php

namespace euventura\UazapiSdk\Tests;

use Saloon\Http\Faking\MockResponse;
use euventura\UazapiSdk\UazapiProfile;

class UazapiProfileTest extends TestCase
{
    private UazapiProfile $profile;

    protected function setUp(): void
    {
        parent::setUp();
        $this->profile = new UazapiProfile($this->connector);
    }

    /** @test */
    public function it_can_update_profile_name()
    {
        $mockData = [
            'success' => true,
            'message' => 'Nome do perfil alterado com sucesso',
            'profile' => [
                'name' => 'Minha Empresa',
                'updated_at' => time()
            ]
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $data = $this->profile->updateName('Minha Empresa');

        $this->assertIsArray($data);
        $this->assertTrue($data['success']);
        $this->assertEquals('Minha Empresa', $data['profile']['name']);
    }

    /** @test */
    public function it_validates_profile_name_length()
    {
        $this->mockClient->addResponse(
            MockResponse::make([
                'error' => 'Nome muito longo ou inválido'
            ], 400)
        );

        $longName = str_repeat('a', 30); // Mais de 25 caracteres
        $data = $this->profile->updateName($longName);

        $this->assertArrayHasKey('error', $data);
    }

    /** @test */
    public function it_can_update_profile_image_with_url()
    {
        $mockData = [
            'success' => true,
            'message' => 'Imagem do perfil alterada com sucesso',
            'profile' => [
                'image_updated' => true,
                'image_removed' => false,
                'updated_at' => time()
            ]
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $data = $this->profile->updateImage('https://exemplo.com/logo.jpg');

        $this->assertIsArray($data);
        $this->assertTrue($data['profile']['image_updated']);
        $this->assertFalse($data['profile']['image_removed']);
    }

    /** @test */
    public function it_can_update_profile_image_with_base64()
    {
        $mockData = [
            'success' => true,
            'message' => 'Imagem do perfil alterada com sucesso',
            'profile' => [
                'image_updated' => true,
                'image_removed' => false,
                'updated_at' => time()
            ]
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $base64Image = 'data:image/jpeg;base64,/9j/4AAQSkZJRg...';
        $data = $this->profile->updateImage($base64Image);

        $this->assertIsArray($data);
        $this->assertTrue($data['profile']['image_updated']);
    }

    /** @test */
    public function it_can_remove_profile_image()
    {
        $mockData = [
            'success' => true,
            'message' => 'Imagem do perfil removida com sucesso',
            'profile' => [
                'image_updated' => false,
                'image_removed' => true,
                'updated_at' => time()
            ]
        ];

        $this->mockClient->addResponse(
            MockResponse::make($mockData, 200)
        );

        $data = $this->profile->removeImage();

        $this->assertIsArray($data);
        $this->assertTrue($data['profile']['image_removed']);
        $this->assertFalse($data['profile']['image_updated']);
    }

    /** @test */
    public function it_handles_invalid_image_format()
    {
        $this->mockClient->addResponse(
            MockResponse::make([
                'error' => 'Formato de imagem inválido ou URL inacessível'
            ], 400)
        );

        $data = $this->profile->updateImage('invalid-url');

        $this->assertArrayHasKey('error', $data);
    }

    /** @test */
    public function it_handles_image_too_large()
    {
        $this->mockClient->addResponse(
            MockResponse::make([
                'error' => 'Imagem muito grande, tamanho máximo permitido excedido'
            ], 413)
        );

        $data = $this->profile->updateImage('https://exemplo.com/huge-image.jpg');

        $this->assertArrayHasKey('error', $data);
    }

    /** @test */
    public function it_handles_no_session_error()
    {
        $this->mockClient->addResponse(
            MockResponse::make(['error' => 'No session'], 401)
        );

        $data = $this->profile->updateName('Test Name');

        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('No session', $data['error']);
    }

    /** @test */
    public function it_handles_rate_limit_exceeded()
    {
        $this->mockClient->addResponse(
            MockResponse::make([
                'error' => 'Limite de alterações excedido ou conta com restrições'
            ], 403)
        );

        $data = $this->profile->updateName('New Name');

        $this->assertArrayHasKey('error', $data);
    }
}

