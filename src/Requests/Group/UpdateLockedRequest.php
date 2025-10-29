<?php

namespace euventura\UazapiSdk\Requests\Group;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update Locked Request
 *
 * Define se apenas administradores podem editar as informações do grupo.
 *
 * @package UazApi\Requests\Group
 */
class UpdateLockedRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param string $groupjid JID do grupo (ex: 120363308883996631@g.us)
     * @param bool $locked true = apenas admins podem editar, false = todos podem editar
     */
    public function __construct(
        protected string $groupjid,
        protected bool   $locked
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/group/updateLocked';
    }

    public function defaultBody(): array
    {
        return [
            'groupjid' => $this->groupjid,
            'locked' => $this->locked,
        ];
    }
}
