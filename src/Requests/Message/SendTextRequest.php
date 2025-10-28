<?php

namespace UazApi\Requests\Message;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class SendTextRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string  $number,
        protected string  $text,
        protected array $choices,
        protected ?bool   $linkPreview = null,
        protected ?string $linkPreviewTitle = null,
        protected ?string $linkPreviewDescription = null,
        protected ?string $linkPreviewImage = null,
        protected ?bool   $linkPreviewLarge = null,
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
        return '/send/text';
    }

    public function defaultBody(): array
    {
        $body = [
            'number' => $this->number,
            'text' => $this->text,
        ];

        if ($this->linkPreview !== null) {
            $body['linkPreview'] = $this->linkPreview;
        }

        if ($this->linkPreviewTitle !== null) {
            $body['linkPreviewTitle'] = $this->linkPreviewTitle;
        }

        if ($this->linkPreviewDescription !== null) {
            $body['linkPreviewDescription'] = $this->linkPreviewDescription;
        }

        if ($this->linkPreviewImage !== null) {
            $body['linkPreviewImage'] = $this->linkPreviewImage;
        }

        if ($this->linkPreviewLarge !== null) {
            $body['linkPreviewLarge'] = $this->linkPreviewLarge;
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

