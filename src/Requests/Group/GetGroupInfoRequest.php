<?php

namespace euventura\UazapiSdk\Requests\Group;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Get Group Info Request
 *
 * Recupera informações completas de um grupo do WhatsApp.
 *
 * @package UazApi\Requests\Group
 */
class GetGroupInfoRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param string $groupjid JID do grupo (ex: 120363153742561022@g.us)
     * @param bool|null $getInviteLink Recuperar link de convite do grupo
     * @param bool|null $getRequestsParticipants Recuperar lista de solicitações pendentes
     * @param bool|null $force Forçar atualização, ignorando cache
     */
    public function __construct(
        protected string $groupjid,
        protected ?bool  $getInviteLink = null,
        protected ?bool  $getRequestsParticipants = null,
        protected ?bool  $force = null
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/group/info';
    }

    public function defaultBody(): array
    {
        $body = [
            'groupjid' => $this->groupjid,
        ];

        if ($this->getInviteLink !== null) {
            $body['getInviteLink'] = $this->getInviteLink;
        }

        if ($this->getRequestsParticipants !== null) {
            $body['getRequestsParticipants'] = $this->getRequestsParticipants;
        }

        if ($this->force !== null) {
            $body['force'] = $this->force;
        }

        return $body;
    }
}
