<?php

namespace UazApi;

use UazApi\Requests\QuickReply\EditQuickReplyRequest;
use UazApi\Requests\QuickReply\GetAllQuickRepliesRequest;

/**
 * UazAPI Quick Reply Resource
 *
 * Classe para gerenciamento de respostas rápidas (templates de mensagens).
 * Permite criar, editar, deletar e listar templates de texto e mídia
 * para agilizar o atendimento.
 *
 * IMPORTANTE: Este recurso armazena respostas rápidas na API, mas não as
 * aplica automaticamente. É necessário um sistema frontend/interface para
 * utilizar essas respostas. Para automação de respostas, use o Chatbot.
 *
 * Tipos de respostas suportados:
 * - text: Mensagem de texto
 * - image: Imagem
 * - video: Vídeo
 * - audio: Áudio
 * - myaudio: Mensagem de áudio alternativa
 * - ptt: Mensagem de voz
 * - document: Documento/arquivo
 *
 * @package UazApi
 * @author UazAPI Team
 * @version 1.0.0
 *
 * @example
 * ```php
 * $connector = new UazapiApiConnector('https://free.uazapi.com', 'seu-token');
 * $quickReply = new UazapiQuickReply($connector);
 *
 * // Criar resposta de texto
 * $quickReply->createText('saudacao', 'Olá! Como posso ajudar?');
 *
 * // Listar todas
 * $respostas = $quickReply->getAll();
 * ```
 */
class UazapiQuickReply extends UazapiResource
{
    /**
     * Listar todas as respostas rápidas
     *
     * Retorna um array com todas as respostas rápidas cadastradas
     * para a instância, incluindo:
     * - ID único da resposta
     * - Atalho/shortcut
     * - Tipo (text, image, video, etc)
     * - Conteúdo (texto ou URL do arquivo)
     * - Nome do documento (se aplicável)
     * - Origem (se foi criada pela API ou pelo WhatsApp)
     * - Datas de criação e atualização
     *
     * @return ?arrayResposta com array de respostas rápidas
     *
     * @example
     * ```php
     * $response = $quickReply->getAll();
     *
     * if ($response->successful()) {
     *     $respostas = $response->json();
     *     foreach ($respostas as $resposta) {
     *         echo "Atalho: " . $resposta['shortCut'];
     *         echo "Tipo: " . $resposta['type'];
     *         echo "Conteúdo: " . $resposta['text'];
     *     }
     * }
     * ```
     */
    public function getAll(): ?array
    {
        return $this->send(new GetAllQuickRepliesRequest());
    }

    /**
     * Criar nova resposta rápida de texto
     *
     * Cria um template de mensagem de texto que pode ser usado
     * posteriormente em interfaces de atendimento.
     *
     * O atalho serve como identificador para buscar a resposta
     * rapidamente em sistemas de interface.
     *
     * @param string $shortCut Atalho único para identificar a resposta (ex: 'saudacao', 'despedida')
     * @param string $text Texto da resposta (pode conter quebras de linha)
     *
     * @return ?arrayResposta com dados da resposta rápida criada
     *
     * @example
     * ```php
     * // Criar resposta simples
     * $quickReply->createText('saudacao', 'Olá! Como posso ajudar?');
     *
     * // Criar resposta com texto multilinha
     * $texto = "Olá!\n\nObrigado pelo contato.\nComo posso ajudar você hoje?";
     * $quickReply->createText('boas-vindas', $texto);
     *
     * // Criar resposta com emoji
     * $quickReply->createText('agradecimento', 'Muito obrigado! 😊');
     * ```
     */
    public function createText(string $shortCut, string $text): ?array
    {
        return $this->send(new EditQuickReplyRequest(
            shortCut: $shortCut,
            type: 'text',
            text: $text
        ));
    }

    /**
     * Criar nova resposta rápida de mídia
     *
     * Cria um template de mensagem com mídia (imagem, vídeo, documento, etc)
     * que pode ser usado posteriormente em interfaces de atendimento.
     *
     * Tipos de mídia suportados:
     * - image: Imagem (JPG, PNG)
     * - video: Vídeo (MP4)
     * - audio: Áudio (MP3, OGG)
     * - myaudio: Áudio alternativo
     * - ptt: Mensagem de voz
     * - document: Documento (PDF, DOCX, etc)
     *
     * @param string $shortCut Atalho único para identificar a resposta
     * @param string $type Tipo de mídia (image, video, audio, document, ptt)
     * @param string $file URL pública ou base64 do arquivo
     * @param string|null $docName Nome do arquivo (obrigatório para tipo document)
     *
     * @return ?arrayResposta com dados da resposta rápida criada
     *
     * @example
     * ```php
     * // Criar resposta com imagem
     * $quickReply->createMedia('catalogo', 'image', 'https://exemplo.com/catalogo.jpg');
     *
     * // Criar resposta com documento
     * $quickReply->createMedia(
     *     'tabela-precos',
     *     'document',
     *     'https://exemplo.com/precos.pdf',
     *     'Tabela de Preços.pdf'
     * );
     *
     * // Criar resposta com vídeo
     * $quickReply->createMedia('tutorial', 'video', 'https://exemplo.com/tutorial.mp4');
     * ```
     */
    public function createMedia(string $shortCut, string $type, string $file, ?string $docName = null): ?array
    {
        return $this->send(new EditQuickReplyRequest(
            shortCut: $shortCut,
            type: $type,
            file: $file,
            docName: $docName
        ));
    }

    /**
     * Atualizar resposta rápida de texto
     *
     * Atualiza o atalho e/ou texto de uma resposta rápida existente.
     * Para obter o ID da resposta, use o método getAll().
     *
     * IMPORTANTE: Respostas originadas do WhatsApp (onWhatsApp=true)
     * não podem ser modificadas.
     *
     * @param string $id ID da resposta rápida a ser atualizada
     * @param string $shortCut Novo atalho
     * @param string $text Novo texto
     *
     * @return ?arrayResposta com dados da resposta rápida atualizada
     *
     * @example
     * ```php
     * // Atualizar resposta existente
     * $quickReply->updateText(
     *     'rb9da9c03637452',
     *     'saudacao2',
     *     'Olá! Bem-vindo à nossa empresa!'
     * );
     * ```
     */
    public function updateText(string $id, string $shortCut, string $text): ?array
    {
        return $this->send(new EditQuickReplyRequest(
            shortCut: $shortCut,
            type: 'text',
            id: $id,
            text: $text
        ));
    }

    /**
     * Atualizar resposta rápida de mídia
     *
     * Atualiza o atalho, tipo e/ou arquivo de uma resposta rápida de mídia.
     * Para obter o ID da resposta, use o método getAll().
     *
     * IMPORTANTE: Respostas originadas do WhatsApp (onWhatsApp=true)
     * não podem ser modificadas.
     *
     * @param string $id ID da resposta rápida a ser atualizada
     * @param string $shortCut Novo atalho
     * @param string $type Novo tipo de mídia
     * @param string $file Nova URL ou base64 do arquivo
     * @param string|null $docName Novo nome do documento (se aplicável)
     *
     * @return ?arrayResposta com dados da resposta rápida atualizada
     *
     * @example
     * ```php
     * // Atualizar resposta de imagem
     * $quickReply->updateMedia(
     *     'rb9da9c03637452',
     *     'catalogo-novo',
     *     'image',
     *     'https://exemplo.com/novo-catalogo.jpg'
     * );
     *
     * // Atualizar resposta de documento
     * $quickReply->updateMedia(
     *     'rb9da9c03637453',
     *     'manual',
     *     'document',
     *     'https://exemplo.com/manual-v2.pdf',
     *     'Manual v2.0.pdf'
     * );
     * ```
     */
    public function updateMedia(string $id, string $shortCut, string $type, string $file, ?string $docName = null): ?array
    {
        return $this->send(new EditQuickReplyRequest(
            shortCut: $shortCut,
            type: $type,
            id: $id,
            file: $file,
            docName: $docName
        ));
    }

    /**
     * Deletar resposta rápida
     *
     * Remove permanentemente uma resposta rápida do sistema.
     * Para obter o ID da resposta, use o método getAll().
     *
     * IMPORTANTE: Respostas originadas do WhatsApp (onWhatsApp=true)
     * não podem ser deletadas.
     *
     * @param string $id ID da resposta rápida a ser removida
     *
     * @return ?arrayResposta confirmando a exclusão
     *
     * @example
     * ```php
     * $response = $quickReply->delete('rb9da9c03637452');
     *
     * if ($response->successful()) {
     *     echo "Resposta rápida deletada com sucesso!";
     * }
     * ```
     */
    public function delete(string $id): ?array
    {
        return $this->send(new EditQuickReplyRequest(
            shortCut: '',
            type: 'text',
            id: $id,
            delete: true
        ));
    }

    /**
     * Editar resposta rápida (método genérico)
     *
     * Método genérico que permite criar, atualizar ou deletar respostas rápidas
     * com controle total sobre todos os parâmetros.
     *
     * Este método é mais flexível que os métodos específicos (createText, updateMedia, etc),
     * mas requer conhecimento de todos os parâmetros disponíveis.
     *
     * Operações:
     * - Criar: Não inclua o parâmetro $id
     * - Atualizar: Inclua o $id existente
     * - Deletar: Inclua o $id e defina $delete=true
     *
     * @param string $shortCut Atalho da resposta
     * @param string $type Tipo (text, image, video, audio, document, ptt)
     * @param string|null $id ID (obrigatório para atualizar ou deletar)
     * @param bool $delete Se true, deleta a resposta (requer $id)
     * @param string|null $text Texto (obrigatório para tipo text)
     * @param string|null $file URL ou base64 (obrigatório para tipos de mídia)
     * @param string|null $docName Nome do documento (opcional, para tipo document)
     *
     * @return ?arrayResposta com resultado da operação
     *
     * @example
     * ```php
     * // Criar resposta de texto
     * $quickReply->edit('ola', 'text', text: 'Olá!');
     *
     * // Atualizar resposta de imagem
     * $quickReply->edit(
     *     shortCut: 'logo',
     *     type: 'image',
     *     id: 'rb9da9c03637452',
     *     file: 'https://exemplo.com/logo-novo.jpg'
     * );
     *
     * // Deletar resposta
     * $quickReply->edit(
     *     shortCut: '',
     *     type: 'text',
     *     id: 'rb9da9c03637452',
     *     delete: true
     * );
     * ```
     */
    public function edit(
        string  $shortCut,
        string  $type,
        ?string $id = null,
        bool    $delete = false,
        ?string $text = null,
        ?string $file = null,
        ?string $docName = null
    ): ?array
    {
        return $this->send(new EditQuickReplyRequest(
            shortCut: $shortCut,
            type: $type,
            id: $id,
            delete: $delete,
            text: $text,
            file: $file,
            docName: $docName
        ));
    }
}

