<?php

namespace euventura\UazapiSdk\Requests\Group;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update Name Request
 *
 * Altera o nome de um grupo do WhatsApp.
 *
 * @package UazApi\Requests\Group
 */
class UpdateNameRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param string $groupjid JID do grupo (ex: 120363339858396166@g.us)
     * @param string $name Novo nome para o grupo (1-25 caracteres)
     */
    public function __construct(
        protected string $groupjid,
        protected string $name
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/group/updateName';
    }

    public function defaultBody(): array
    {
        return [
            'groupjid' => $this->groupjid,
            'name' => $this->name,
        ];
    }
}
