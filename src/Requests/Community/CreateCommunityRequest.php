<?php

namespace euventura\UazapiSdk\Requests\Community;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create Community Request
 *
 * Cria uma nova comunidade no WhatsApp.
 * Uma comunidade permite agrupar múltiplos grupos relacionados.
 *
 * @package UazApi\Requests\Community
 */
class CreateCommunityRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param string $name Nome da comunidade
     */
    public function __construct(
        protected string $name
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/community/create';
    }

    public function defaultBody(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
