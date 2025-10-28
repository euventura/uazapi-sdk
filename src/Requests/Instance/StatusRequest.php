<?php

namespace UazApi\Requests\Instance;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class StatusRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/instance/status';
    }
}

