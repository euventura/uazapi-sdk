<?php

namespace UazApi\Requests\Message;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class SendMediaRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string  $number,
        protected string  $type,
        protected string  $file,
        protected ?string $text = null,
        protected ?string $docName = null,
        protected ?string $replyid = null,
        protected ?string $mentions = null,
        protected ?bool   $readchat = null,
        protected ?bool   $readmessages = null,
        protected ?int    $messageDelay = null,
        protected ?bool   $forward = null,
        protected ?string $track_source = null,
        protected ?string $track_id = null
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/send/media';
    }

    public function defaultBody(): array
    {
        $body = [
            'number' => $this->number,
            'type' => $this->type,
            'file' => $this->file,
        ];

        if ($this->text !== null) {
            $body['text'] = $this->text;
        }

        if ($this->docName !== null) {
            $body['docName'] = $this->docName;
        }

        if ($this->replyid !== null) {
            $body['replyid'] = $this->replyid;
        }

        if ($this->mentions !== null) {
            $body['mentions'] = $this->mentions;
        }

        if ($this->readchat !== null) {
            $body['readchat'] = $this->readchat;
        }

        if ($this->readmessages !== null) {
            $body['readmessages'] = $this->readmessages;
        }

        if ($this->messageDelay !== null) {
            $body['delay'] = $this->messageDelay;
        }

        if ($this->forward !== null) {
            $body['forward'] = $this->forward;
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

