<?php

namespace euventura\UazapiSdk\Requests\Instance;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class ConnectRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected ?string $phone = null
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/instance/connect';
    }

    public function defaultBody(): array
    {
        $body = [];

        if ($this->phone !== null) {
            $body['phone'] = $this->phone;
        }

        return $body;
    }
}

