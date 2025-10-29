<?php

namespace euventura\UazapiSdk;

use euventura\UazapiSdk\Requests\Profile\UpdateProfileImageRequest;
use euventura\UazapiSdk\Requests\Profile\UpdateProfileNameRequest;

/**
 * UazAPI Profile Resource
 *
 * Classe para gerenciamento do perfil da conta WhatsApp.
 * Fornece métodos para alterar nome e foto de perfil que são
 * visíveis para todos os contatos do WhatsApp.
 *
 * @package UazApi
 * @author UazAPI Team
 * @version 1.0.0
 *
 * @example
 * ```php
 * $connector = new UazapiApiConnector('https://free.uazapi.com', 'seu-token');
 * $profile = new UazapiProfile($connector);
 *
 * // Alterar nome do perfil
 * $profile->updateName('Minha Loja');
 *
 * // Alterar foto do perfil
 * $profile->updateImage('https://exemplo.com/logo.jpg');
 * ```
 */
class UazapiProfile extends UazapiResource
{
    /**
     * Alterar nome do perfil do WhatsApp
     *
     * Atualiza o nome de exibição do perfil da conta WhatsApp.
     * Este nome será visível para todos os seus contatos.
     *
     * Observações:
     * - O WhatsApp pode ter limites de alterações por período
     * - O nome deve ter no máximo 25 caracteres
     * - A instância deve estar conectada
     *
     * @param string $name Novo nome do perfil (máximo 25 caracteres)
     *
     * @return ?arrayResposta confirmando a alteração com timestamp
     *
     * @example
     * ```php
     * $response = $profile->updateName('Minha Empresa - Atendimento');
     *
     * if ($response->successful()) {
     *     $data = $response->json();
     *     echo "Nome atualizado: " . $data['profile']['name'];
     * }
     * ```
     */
    public function updateName(string $name): ?array
    {
        return $this->send(new UpdateProfileNameRequest($name));
    }

    /**
     * Alterar imagem do perfil do WhatsApp
     *
     * Atualiza a foto de perfil da conta WhatsApp.
     * A imagem será visível para todos os seus contatos.
     *
     * Formatos aceitos:
     * - URL pública da imagem (http/https)
     * - String base64 da imagem (data:image/jpeg;base64,...)
     * - Comando "remove" ou "delete" para remover a foto atual
     *
     * Especificações da imagem:
     * - Formato recomendado: JPEG
     * - Dimensões recomendadas: 640x640 pixels
     * - A imagem será processada e redimensionada automaticamente
     *
     * @param string $image URL da imagem, base64 ou "remove"/"delete" para remover
     *
     * @return ?arrayResposta confirmando a alteração
     *
     * @example
     * ```php
     * // Alterar foto com URL
     * $profile->updateImage('https://exemplo.com/logo.jpg');
     *
     * // Alterar foto com base64
     * $profile->updateImage('data:image/jpeg;base64,/9j/4AAQ...');
     *
     * // Remover foto
     * $profile->updateImage('remove');
     * ```
     */
    public function updateImage(string $image): ?array
    {
        return $this->send(new UpdateProfileImageRequest($image));
    }

    /**
     * Remover imagem do perfil do WhatsApp
     *
     * Remove a foto de perfil atual da conta WhatsApp.
     * Após a remoção, o perfil ficará sem foto.
     *
     * Este método é um atalho para updateImage('remove').
     *
     * @return ?arrayResposta confirmando a remoção
     *
     * @example
     * ```php
     * $response = $profile->removeImage();
     *
     * if ($response->successful()) {
     *     echo "Foto de perfil removida!";
     * }
     * ```
     */
    public function removeImage(): ?array
    {
        return $this->send(new UpdateProfileImageRequest('remove'));
    }
}

