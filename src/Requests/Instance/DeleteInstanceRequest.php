<?php

namespace euventura\UazapiSdk\Requests\Instance;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteInstanceRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function resolveEndpoint(): string
    {
        return '/instance';
    }
}

