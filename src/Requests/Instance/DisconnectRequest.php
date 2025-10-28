<?php

namespace UazApi\Requests\Instance;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DisconnectRequest extends Request
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/instance/disconnect';
    }
}

