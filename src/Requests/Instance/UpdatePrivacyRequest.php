<?php

namespace euventura\UazapiSdk\Requests\Instance;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdatePrivacyRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected array $privacySettings
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/instance/privacy';
    }

    public function defaultBody(): array
    {
        return $this->privacySettings;
    }
}

