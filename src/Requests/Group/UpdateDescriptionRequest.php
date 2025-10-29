<?php

namespace euventura\UazapiSdk\Requests\Group;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update Description Request
 *
 * Altera a descrição (tópico) do grupo WhatsApp.
 *
 * @package UazApi\Requests\Group
 */
class UpdateDescriptionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param string $groupjid JID do grupo (ex: 120363339858396166@g.us)
     * @param string $description Nova descrição/tópico do grupo (máx. 512 caracteres)
     */
    public function __construct(
        protected string $groupjid,
        protected string $description
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/group/updateDescription';
    }

    public function defaultBody(): array
    {
        return [
            'groupjid' => $this->groupjid,
            'description' => $this->description,
        ];
    }
}
