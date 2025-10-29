<?php

namespace euventura\UazapiSdk\Requests\Group;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Invite Link Request
 *
 * Gera link de convite para um grupo.
 *
 * @package UazApi\Requests\Group
 */
class GetInviteLinkRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param string $groupJID JID do grupo (ex: 120363153742561022@g.us)
     */
    public function __construct(
        protected string $groupJID
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/group/invitelink/' . $this->groupJID;
    }
}
