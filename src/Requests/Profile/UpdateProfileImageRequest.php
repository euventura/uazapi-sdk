<?php

namespace UazApi\Requests\Profile;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateProfileImageRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $image
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/profile/image';
    }

    public function defaultBody(): array
    {
        return [
            'image' => $this->image,
        ];
    }
}

