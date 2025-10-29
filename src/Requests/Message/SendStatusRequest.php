<?php

namespace euventura\UazapiSdk\Requests\Message;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Send Status Request
 *
 * Envia um story (status) com suporte para texto, imagem, vídeo e áudio.
 *
 * Tipos suportados:
 * - text: Texto com estilo e cor de fundo
 * - image: Imagens com legenda opcional
 * - video: Vídeos com thumbnail e legenda
 * - audio: Áudio normal
 * - myaudio: Áudio alternativo
 * - ptt: Mensagem de voz (Push-to-Talk)
 *
 * @package UazApi\Requests\Message
 */
class SendStatusRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param string $type Tipo do status: text, image, video, audio, myaudio, ptt
     * @param string|null $text Texto principal ou legenda
     * @param int|null $background_color Cor de fundo (1-19, apenas para type=text)
     * @param int|null $font Estilo da fonte (0-8, apenas para type=text)
     * @param string|null $file URL ou Base64 do arquivo de mídia
     * @param string|null $thumbnail URL ou Base64 da miniatura (opcional para vídeos)
     * @param string|null $mimetype MIME type do arquivo (opcional)
     * @param string|null $track_source Origem do rastreamento
     * @param string|null $track_id ID de rastreamento
     */
    public function __construct(
        protected string  $type,
        protected ?string $text = null,
        protected ?int    $background_color = null,
        protected ?int    $font = null,
        protected ?string $file = null,
        protected ?string $thumbnail = null,
        protected ?string $mimetype = null,
        protected ?string $track_source = null,
        protected ?string $track_id = null
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/send/status';
    }

    public function defaultBody(): array
    {
        $body = [
            'type' => $this->type,
        ];

        if ($this->text !== null) {
            $body['text'] = $this->text;
        }

        if ($this->background_color !== null) {
            $body['background_color'] = $this->background_color;
        }

        if ($this->font !== null) {
            $body['font'] = $this->font;
        }

        if ($this->file !== null) {
            $body['file'] = $this->file;
        }

        if ($this->thumbnail !== null) {
            $body['thumbnail'] = $this->thumbnail;
        }

        if ($this->mimetype !== null) {
            $body['mimetype'] = $this->mimetype;
        }

        if ($this->track_source !== null) {
            $body['track_source'] = $this->track_source;
        }

        if ($this->track_id !== null) {
            $body['track_id'] = $this->track_id;
        }

        return $body;
    }
}
