<?php

namespace UazApi\Requests\Instance;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetPrivacyRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/instance/privacy';
    }
}

