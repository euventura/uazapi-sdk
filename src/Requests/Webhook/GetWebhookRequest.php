<?php

namespace euventura\UazapiSdk\Requests\Webhook;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetWebhookRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/webhook';
    }
}

