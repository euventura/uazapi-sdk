<?php

namespace euventura\UazapiSdk\Requests\Group;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Get Invite Info Request
 *
 * Retorna informações detalhadas de um grupo usando um código de convite.
 *
 * @package UazApi\Requests\Group
 */
class GetInviteInfoRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param string $inviteCode Código de convite ou URL completa (ex: IYnl5Zg9bUcJD32rJrDzO7 ou https://chat.whatsapp.com/...)
     */
    public function __construct(
        protected string $inviteCode
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/group/inviteInfo';
    }

    public function defaultBody(): array
    {
        return [
            'inviteCode' => $this->inviteCode,
        ];
    }
}
