<?php

namespace euventura\UazapiSdk\Requests\Group;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Leave Group Request
 *
 * Remove o usuário atual de um grupo específico do WhatsApp.
 *
 * @package UazApi\Requests\Group
 */
class LeaveGroupRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param string $groupjid JID do grupo (ex: 120363324255083289@g.us)
     */
    public function __construct(
        protected string $groupjid
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/group/leave';
    }

    public function defaultBody(): array
    {
        return [
            'groupjid' => $this->groupjid,
        ];
    }
}
