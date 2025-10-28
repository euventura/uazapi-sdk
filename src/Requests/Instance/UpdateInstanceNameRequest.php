<?php

namespace UazApi\Requests\Instance;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateInstanceNameRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $name
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/instance/updateInstanceName';
    }

    public function defaultBody(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}

