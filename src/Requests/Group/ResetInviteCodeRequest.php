<?php

namespace euventura\UazapiSdk\Requests\Group;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Reset Invite Code Request
 *
 * Gera um novo código de convite para o grupo, invalidando o anterior.
 *
 * @package UazApi\Requests\Group
 */
class ResetInviteCodeRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param string $groupjid JID do grupo (ex: 120363308883996631@g.us)
     */
    public function __construct(
        protected string $groupjid
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/group/resetInviteCode';
    }

    public function defaultBody(): array
    {
        return [
            'groupjid' => $this->groupjid,
        ];
    }
}
