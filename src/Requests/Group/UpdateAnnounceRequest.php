<?php

namespace euventura\UazapiSdk\Requests\Group;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update Announce Request
 *
 * Define as permissões de envio de mensagens no grupo.
 * Quando ativado, apenas administradores podem enviar mensagens.
 *
 * @package UazApi\Requests\Group
 */
class UpdateAnnounceRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param string $groupjid JID do grupo (ex: 120363339858396166@g.us)
     * @param bool $announce true = apenas admins podem enviar, false = todos podem enviar
     */
    public function __construct(
        protected string $groupjid,
        protected bool   $announce
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/group/updateAnnounce';
    }

    public function defaultBody(): array
    {
        return [
            'groupjid' => $this->groupjid,
            'announce' => $this->announce,
        ];
    }
}
