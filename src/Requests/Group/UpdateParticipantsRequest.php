<?php

namespace euventura\UazapiSdk\Requests\Group;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update Participants Request
 *
 * Gerencia participantes do grupo através de diferentes ações:
 * add, remove, promote, demote, approve, reject.
 *
 * @package UazApi\Requests\Group
 */
class UpdateParticipantsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param string $groupjid JID do grupo (ex: 120363308883996631@g.us)
     * @param string $action Ação: add, remove, promote, demote, approve, reject
     * @param array<string> $participants Lista de números ou JIDs dos participantes
     */
    public function __construct(
        protected string $groupjid,
        protected string $action,
        protected array  $participants
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/group/updateParticipants';
    }

    public function defaultBody(): array
    {
        return [
            'groupjid' => $this->groupjid,
            'action' => $this->action,
            'participants' => $this->participants,
        ];
    }
}
