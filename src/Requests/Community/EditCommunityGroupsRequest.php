<?php

namespace euventura\UazapiSdk\Requests\Community;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Edit Community Groups Request
 *
 * Adiciona ou remove grupos de uma comunidade do WhatsApp.
 *
 * @package UazApi\Requests\Community
 */
class EditCommunityGroupsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param string $community JID da comunidade (ex: 120363153742561022@g.us)
     * @param string $action Ação: add ou remove
     * @param array<string> $groupjids Lista de JIDs dos grupos
     */
    public function __construct(
        protected string $community,
        protected string $action,
        protected array  $groupjids
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/community/editgroups';
    }

    public function defaultBody(): array
    {
        return [
            'community' => $this->community,
            'action' => $this->action,
            'groupjids' => $this->groupjids,
        ];
    }
}
