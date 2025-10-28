<?php

namespace UazApi\Requests\Message;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class SendButtonsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string  $number,
        protected string  $text,
        protected array   $choices,
        protected ?string $footerText = null,
        protected ?string $listButton = null,
        protected ?string $selectableCount = null,
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
        return '/send/menu';
    }

    public function defaultBody(): array
    {
        return array_filter([
            'number' => $this->number,
            'text' => $this->text,
            'choices' => $this->choices,
            'footerText' => $this->footerText,
            'listButton' => $this->listButton,
            'selectableCount' => $this->selectableCount,
            'replyid' => $this->replyid,
            'mentions' => $this->mentions,
            'readchat' => $this->readchat,
            'readmessages' => $this->readmessages,
            'delay' => $this->messageDelay,
            'forward' => $this->forward,
            'track_source' => $this->track_source,
            'track_id' => $this->track_id,
        ]);
    }
}

