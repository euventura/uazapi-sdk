<?php

namespace UazApi\Requests\Webhook;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class ConfigureWebhookRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string  $url,
        protected array   $events,
        protected ?bool   $enabled = true,
        protected ?array  $excludeMessages = null,
        protected ?bool   $addUrlEvents = false,
        protected ?bool   $addUrlTypesMessages = false,
        protected ?string $action = null,
        protected ?string $id = null
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/webhook';
    }

    public function defaultBody(): array
    {
        $body = [
            'url' => $this->url,
            'events' => $this->events,
            'enabled' => $this->enabled,
            'addUrlEvents' => $this->addUrlEvents,
            'AddUrlTypesMessages' => $this->addUrlTypesMessages,
        ];

        if ($this->excludeMessages !== null) {
            $body['excludeMessages'] = $this->excludeMessages;
        }

        if ($this->action !== null) {
            $body['action'] = $this->action;
        }

        if ($this->id !== null) {
            $body['id'] = $this->id;
        }

        return $body;
    }
}

