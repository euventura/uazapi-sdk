<?php

namespace UazApi;

use Saloon\Http\Response;
use UazApi\Requests\Message\SendContactRequest;
use UazApi\Requests\Message\SendLocationRequest;
use UazApi\Requests\Message\SendMediaRequest;
use UazApi\Requests\Message\SendTextRequest;

/**
 * UazAPI Message Resource
 *
 * Classe para envio de mensagens do WhatsApp com diferentes tipos de conteúdo.
 * Suporta texto, imagens, vídeos, áudios, documentos, stickers, contatos e localização.
 *
 * Todos os métodos suportam opções comuns através do parâmetro $options:
 * - delay: Atraso em ms antes do envio (mostra "digitando..." ou "gravando...")
 * - readchat: Marca conversa como lida
 * - readmessages: Marca mensagens recebidas como lidas
 * - replyid: ID da mensagem para responder
 * - mentions: Números para mencionar (separados por vírgula ou "all")
 * - forward: Marca mensagem como encaminhada
 * - track_source: Origem do rastreamento
 * - track_id: ID de rastreamento (aceita duplicados)
 *
 * @package UazApi
 * @author UazAPI Team
 * @version 1.0.0
 *
 * @example
 * ```php
 * $connector = new UazapiApiConnector('https://free.uazapi.com', 'seu-token');
 * $message = new UazapiMessage($connector);
 *
 * // Enviar texto
 * $message->sendText('5511999999999', 'Olá!');
 *
 * // Enviar imagem
 * $message->sendImage('5511999999999', 'https://exemplo.com/foto.jpg', 'Legenda');
 * ```
 */
class UazapiMessage extends UazapiResource
{
    /**
     * Enviar mensagem de texto
     *
     * Envia uma mensagem de texto simples ou com preview de links.
     * Suporta placeholders dinâmicos que são substituídos automaticamente:
     * - {{name}}, {{first_name}}: Nome do contato
     * - {{lead_*}}: Campos do lead (email, telefone, etc)
     * - Campos personalizados via fieldsMap
     *
     * Opções específicas de texto (via $options):
     * - linkPreview: Ativa preview de links (bool)
     * - linkPreviewTitle: Título personalizado do preview
     * - linkPreviewDescription: Descrição personalizada do preview
     * - linkPreviewImage: URL ou base64 da imagem do preview
     * - linkPreviewLarge: Se true, gera preview grande com upload de imagem
     *
     * @param string $number Número do destinatário no formato internacional (ex: 5511999999999)
     *                       Para grupos, use o ID do grupo (ex: 120363012345678901@g.us)
     * @param string $text Texto da mensagem (aceita placeholders como {{name}})
     * @param array<string, mixed> $options Opções adicionais de envio e preview
     *
     * @return Response Resposta com dados da mensagem enviada
     *
     * @example
     * ```php
     * // Texto simples
     * $message->sendText('5511999999999', 'Olá! Como posso ajudar?');
     *
     * // Com placeholder
     * $message->sendText('5511999999999', 'Olá {{name}}! Seu email é {{lead_email}}?');
     *
     * // Com preview de link
     * $message->sendText('5511999999999', 'Confira: https://exemplo.com', [
     *     'linkPreview' => true,
     *     'delay' => 2000
     * ]);
     *
     * // Com preview personalizado
     * $message->sendText('5511999999999', 'https://exemplo.com', [
     *     'linkPreview' => true,
     *     'linkPreviewTitle' => 'Título Custom',
     *     'linkPreviewDescription' => 'Descrição custom',
     *     'linkPreviewImage' => 'https://exemplo.com/thumb.jpg',
     *     'linkPreviewLarge' => true
     * ]);
     *
     * // Respondendo mensagem em grupo com menções
     * $message->sendText('120363012345678901@g.us', 'Olá @todos!', [
     *     'replyid' => '3EB0538DA65A59F6D8A251',
     *     'mentions' => 'all',
     *     'delay' => 1000
     * ]);
     * ```
     */
    public function sendText(string $number, string $text, array $options = []): Response
    {
        return $this->send(new SendTextRequest(
            $number,
            $text
        ));
    }

    /**
     * Enviar imagem
     *
     * Envia uma imagem com legenda opcional.
     * A imagem pode ser fornecida via URL pública ou string base64.
     *
     * Formatos suportados:
     * - JPEG (recomendado)
     * - PNG
     * - GIF (sem animação)
     *
     * @param string $number Número do destinatário (formato internacional)
     * @param string $file URL pública ou base64 da imagem
     * @param string|null $caption Legenda/texto descritivo da imagem (aceita placeholders)
     * @param array<string, mixed> $options Opções adicionais de envio
     *
     * @return Response Resposta com dados da mensagem enviada
     *
     * @example
     * ```php
     * // Imagem simples
     * $message->sendImage('5511999999999', 'https://exemplo.com/foto.jpg');
     *
     * // Com legenda
     * $message->sendImage('5511999999999', 'https://exemplo.com/foto.jpg', 'Veja esta foto!');
     *
     * // Com base64
     * $message->sendImage('5511999999999', 'data:image/jpeg;base64,/9j/4AAQ...', 'Foto');
     *
     * // Com opções
     * $message->sendImage('5511999999999', 'https://exemplo.com/foto.jpg', 'Promoção!', [
     *     'delay' => 3000,
     *     'forward' => true,
     *     'track_source' => 'marketing'
     * ]);
     * ```
     */
    public function sendImage(string $number, string $file, ?string $caption = null, array $options = []): Response
    {
        return $this->sendMedia($number, 'image', $file, $caption, $options);
    }

    /**
     * Enviar vídeo
     *
     * Envia um vídeo com legenda opcional.
     * O vídeo pode ser fornecido via URL pública ou string base64.
     *
     * Formatos suportados:
     * - MP4 (recomendado e único suportado pelo WhatsApp)
     *
     * Observações:
     * - Vídeos muito grandes podem demorar para processar
     * - Thumbnails são gerados automaticamente
     *
     * @param string $number Número do destinatário (formato internacional)
     * @param string $file URL pública ou base64 do vídeo
     * @param string|null $caption Legenda/texto descritivo do vídeo (aceita placeholders)
     * @param array<string, mixed> $options Opções adicionais de envio
     *
     * @return Response Resposta com dados da mensagem enviada
     *
     * @example
     * ```php
     * // Vídeo simples
     * $message->sendVideo('5511999999999', 'https://exemplo.com/video.mp4');
     *
     * // Com legenda
     * $message->sendVideo('5511999999999', 'https://exemplo.com/video.mp4', 'Confira!');
     *
     * // Com opções
     * $message->sendVideo('5511999999999', 'https://exemplo.com/video.mp4', 'Tutorial', [
     *     'delay' => 5000,
     *     'track_id' => 'video-tutorial-001'
     * ]);
     * ```
     */
    public function sendVideo(string $number, string $file, ?string $caption = null, array $options = []): Response
    {
        return $this->sendMedia($number, 'video', $file, $caption, $options);
    }

    /**
     * Enviar documento
     *
     * Envia um arquivo/documento com nome e legenda opcionais.
     * O documento pode ser fornecido via URL pública ou string base64.
     *
     * Formatos suportados:
     * - PDF
     * - DOC/DOCX
     * - XLS/XLSX
     * - PPT/PPTX
     * - TXT
     * - ZIP
     * - E outros formatos de arquivo
     *
     * @param string $number Número do destinatário (formato internacional)
     * @param string $file URL pública ou base64 do documento
     * @param string|null $docName Nome do arquivo com extensão (ex: "Contrato.pdf")
     * @param string|null $caption Legenda/texto descritivo (aceita placeholders)
     * @param array<string, mixed> $options Opções adicionais de envio
     *
     * @return Response Resposta com dados da mensagem enviada
     *
     * @example
     * ```php
     * // Documento simples
     * $message->sendDocument('5511999999999', 'https://exemplo.com/doc.pdf', 'Contrato.pdf');
     *
     * // Com legenda
     * $message->sendDocument(
     *     '5511999999999',
     *     'https://exemplo.com/relatorio.pdf',
     *     'Relatório Mensal.pdf',
     *     'Segue o relatório solicitado'
     * );
     *
     * // Com opções
     * $message->sendDocument(
     *     '5511999999999',
     *     'https://exemplo.com/tabela.xlsx',
     *     'Preços.xlsx',
     *     'Tabela de preços atualizada',
     *     ['track_source' => 'vendas']
     * );
     * ```
     */
    public function sendDocument(string $number, string $file, ?string $docName = null, ?string $caption = null, array $options = []): Response
    {
        $options['docName'] = $docName;
        return $this->sendMedia($number, 'document', $file, $caption, $options);
    }

    /**
     * Enviar áudio
     *
     * Envia um arquivo de áudio comum (não é mensagem de voz).
     * O áudio aparecerá como arquivo para ser reproduzido.
     *
     * Formatos suportados:
     * - MP3 (recomendado)
     * - OGG
     * - WAV
     * - M4A
     *
     * Para enviar mensagem de voz (PTT), use sendVoice()
     *
     * @param string $number Número do destinatário (formato internacional)
     * @param string $file URL pública ou base64 do áudio
     * @param array<string, mixed> $options Opções adicionais de envio
     *
     * @return Response Resposta com dados da mensagem enviada
     *
     * @example
     * ```php
     * // Áudio simples
     * $message->sendAudio('5511999999999', 'https://exemplo.com/musica.mp3');
     *
     * // Com opções
     * $message->sendAudio('5511999999999', 'https://exemplo.com/podcast.mp3', [
     *     'delay' => 2000,
     *     'track_id' => 'podcast-ep-001'
     * ]);
     * ```
     */
    public function sendAudio(string $number, string $file, array $options = []): Response
    {
        return $this->sendMedia($number, 'audio', $file, null, $options);
    }

    /**
     * Enviar mensagem de voz (PTT - Push to Talk)
     *
     * Envia um áudio como mensagem de voz.
     * Aparece com o ícone de microfone e mostra a duração.
     *
     * Formatos suportados:
     * - OGG (recomendado para PTT)
     * - MP3
     *
     * Durante o envio com delay, mostra "Gravando áudio..." para o destinatário.
     *
     * @param string $number Número do destinatário (formato internacional)
     * @param string $file URL pública ou base64 do áudio
     * @param array<string, mixed> $options Opções adicionais de envio
     *
     * @return Response Resposta com dados da mensagem enviada
     *
     * @example
     * ```php
     * // Mensagem de voz simples
     * $message->sendVoice('5511999999999', 'https://exemplo.com/audio.ogg');
     *
     * // Com delay (mostra "Gravando áudio...")
     * $message->sendVoice('5511999999999', 'https://exemplo.com/audio.ogg', [
     *     'delay' => 3000
     * ]);
     * ```
     */
    public function sendVoice(string $number, string $file, array $options = []): Response
    {
        return $this->sendMedia($number, 'ptt', $file, null, $options);
    }

    /**
     * Enviar sticker/figurinha
     *
     * Envia uma figurinha/sticker animada ou estática.
     *
     * Formatos suportados:
     * - WEBP (estático ou animado)
     *
     * Especificações:
     * - Dimensões: 512x512 pixels (recomendado)
     * - Tamanho máximo: ~100KB para estáticos, ~500KB para animados
     *
     * @param string $number Número do destinatário (formato internacional)
     * @param string $file URL pública ou base64 do sticker (formato WEBP)
     * @param array<string, mixed> $options Opções adicionais de envio
     *
     * @return Response Resposta com dados da mensagem enviada
     *
     * @example
     * ```php
     * // Sticker simples
     * $message->sendSticker('5511999999999', 'https://exemplo.com/sticker.webp');
     *
     * // Com opções
     * $message->sendSticker('5511999999999', 'https://exemplo.com/sticker.webp', [
     *     'delay' => 1000,
     *     'replyid' => '3EB0538DA65A59F6D8A251'
     * ]);
     * ```
     */
    public function sendSticker(string $number, string $file, array $options = []): Response
    {
        return $this->sendMedia($number, 'sticker', $file, null, $options);
    }

    /**
     * Enviar mídia genérica
     *
     * Método genérico para enviar qualquer tipo de mídia.
     * Os métodos específicos (sendImage, sendVideo, etc) são atalhos para este método.
     *
     * Tipos suportados:
     * - image: Imagem
     * - video: Vídeo
     * - document: Documento/arquivo
     * - audio: Áudio comum
     * - myaudio: Mensagem de áudio alternativa
     * - ptt: Mensagem de voz (Push-to-Talk)
     * - sticker: Figurinha
     *
     * @param string $number Número do destinatário (formato internacional)
     * @param string $type Tipo de mídia (image, video, document, audio, ptt, sticker)
     * @param string $file URL pública ou base64 do arquivo
     * @param string|null $caption Legenda/texto descritivo (aceita placeholders)
     * @param array<string, mixed> $options Opções adicionais (docName para documents, etc)
     *
     * @return Response Resposta com dados da mensagem enviada
     *
     * @example
     * ```php
     * // Enviar imagem
     * $message->sendMedia('5511999999999', 'image', 'https://exemplo.com/foto.jpg', 'Legenda');
     *
     * // Enviar documento
     * $message->sendMedia('5511999999999', 'document', 'https://exemplo.com/doc.pdf', 'Veja', [
     *     'docName' => 'Documento.pdf',
     *     'delay' => 2000
     * ]);
     * ```
     */
    public function sendMedia(string $number, string $type, string $file, ?string $caption = null, array $options = []): Response
    {
        return $this->send(new SendMediaRequest(
            number: $number,
            type: $type,
            file: $file,
            text: $caption,
            docName: $options['docName'] ?? null,
            replyid: $options['replyid'] ?? null,
            mentions: $options['mentions'] ?? null,
            readchat: $options['readchat'] ?? null,
            readmessages: $options['readmessages'] ?? null,
            forward: $options['forward'] ?? null,
            track_source: $options['track_source'] ?? null,
            track_id: $options['track_id'] ?? null
        ));
    }

    /**
     * Enviar contato (vCard)
     *
     * Envia um ou múltiplos cartões de contato no formato vCard.
     * Os contatos aparecem como cartões clicáveis no WhatsApp.
     *
     * Cada contato deve conter:
     * - fullName: Nome completo do contato
     * - waid: Número do WhatsApp (apenas números)
     * - phoneNumber: Número formatado para exibição
     *
     * @param string $number Número do destinatário (formato internacional)
     * @param array<int, array<string, string>> $contacts Array de contatos com fullName, waid e phoneNumber
     * @param array<string, mixed> $options Opções adicionais de envio
     *
     * @return Response Resposta com dados da mensagem enviada
     *
     * @example
     * ```php
     * // Enviar um contato
     * $message->sendContact('5511999999999', [
     *     [
     *         'fullName' => 'João Silva',
     *         'waid' => '5511888888888',
     *         'phoneNumber' => '+55 11 88888-8888'
     *     ]
     * ]);
     *
     * // Enviar múltiplos contatos
     * $message->sendContact('5511999999999', [
     *     [
     *         'fullName' => 'João Silva',
     *         'waid' => '5511888888888',
     *         'phoneNumber' => '+55 11 88888-8888'
     *     ],
     *     [
     *         'fullName' => 'Maria Santos',
     *         'waid' => '5511777777777',
     *         'phoneNumber' => '+55 11 77777-7777'
     *     ]
     * ]);
     *
     * // Com opções
     * $message->sendContact('5511999999999', [
     *     ['fullName' => 'Suporte', 'waid' => '5511666666666', 'phoneNumber' => '+55 11 66666-6666']
     * ], [
     *     'delay' => 1000,
     *     'track_source' => 'atendimento'
     * ]);
     * ```
     */
    public function sendContact(string $number, array $contacts, array $options = []): Response
    {
        return $this->send(new SendContactRequest(
            number: $number,
            contacts: $contacts,
            replyid: $options['replyid'] ?? null,
            mentions: $options['mentions'] ?? null,
            readchat: $options['readchat'] ?? null,
            readmessages: $options['readmessages'] ?? null,
            messageDelay: $options['delay'] ?? null,
            forward: $options['forward'] ?? null,
            track_source: $options['track_source'] ?? null,
            track_id: $options['track_id'] ?? null
        ));
    }

    /**
     * Enviar localização
     *
     * Envia uma localização geográfica com coordenadas GPS.
     * Opcionalmente pode incluir nome do local e endereço.
     *
     * A localização aparece como um mapa interativo no WhatsApp.
     *
     * @param string $number Número do destinatário (formato internacional)
     * @param float $latitude Latitude do local (ex: -23.5505199)
     * @param float $longitude Longitude do local (ex: -46.6333094)
     * @param string|null $name Nome do local (ex: "Avenida Paulista")
     * @param string|null $address Endereço completo do local
     * @param array<string, mixed> $options Opções adicionais de envio
     *
     * @return Response Resposta com dados da mensagem enviada
     *
     * @example
     * ```php
     * // Localização simples (apenas coordenadas)
     * $message->sendLocation('5511999999999', -23.5505199, -46.6333094);
     *
     * // Com nome do local
     * $message->sendLocation(
     *     '5511999999999',
     *     -23.5505199,
     *     -46.6333094,
     *     'Avenida Paulista'
     * );
     *
     * // Completo com nome e endereço
     * $message->sendLocation(
     *     '5511999999999',
     *     -23.5505199,
     *     -46.6333094,
     *     'Avenida Paulista',
     *     'Av. Paulista, 1578 - Bela Vista, São Paulo - SP, 01310-200'
     * );
     *
     * // Com opções
     * $message->sendLocation(
     *     '5511999999999',
     *     -23.5505199,
     *     -46.6333094,
     *     'Nossa Loja',
     *     'Rua Exemplo, 123',
     *     ['delay' => 2000]
     * );
     * ```
     */
    public function sendLocation(
        string  $number,
        float   $latitude,
        float   $longitude,
        ?string $name = null,
        ?string $address = null,
        array   $options = []
    ): Response
    {
        return $this->send(new SendLocationRequest(
            number: $number,
            latitude: $latitude,
            longitude: $longitude,
            name: $name,
            address: $address,
            replyid: $options['replyid'] ?? null,
            mentions: $options['mentions'] ?? null,
            readchat: $options['readchat'] ?? null,
            readmessages: $options['readmessages'] ?? null,
            messageDelay: $options['delay'] ?? null,
            forward: $options['forward'] ?? null,
            track_source: $options['track_source'] ?? null,
            track_id: $options['track_id'] ?? null
        ));
    }
}

