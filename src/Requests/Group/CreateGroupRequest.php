<?php

namespace euventura\UazapiSdk\Requests\Group;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create Group Request
 *
 * Cria um novo grupo no WhatsApp com nome e lista de participantes.
 *
 * @package UazApi\Requests\Group
 */
class CreateGroupRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param string $name Nome do grupo (1-25 caracteres)
     * @param array<string> $participants Lista de números dos participantes (formato internacional sem +)
     */
    public function __construct(
        protected string $name,
        protected array  $participants
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/group/create';
    }

    public function defaultBody(): array
    {
        return [
            'name' => $this->name,
            'participants' => $this->participants,
        ];
    }
}
