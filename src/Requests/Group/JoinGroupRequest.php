<?php

namespace euventura\UazapiSdk\Requests\Group;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Join Group Request
 *
 * Permite entrar em um grupo do WhatsApp usando um código de convite.
 *
 * @package UazApi\Requests\Group
 */
class JoinGroupRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param string $inviteCode Código de convite ou URL completa (ex: https://chat.whatsapp.com/IYnl5Zg9bUcJD32rJrDzO7)
     */
    public function __construct(
        protected string $inviteCode
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/group/join';
    }

    public function defaultBody(): array
    {
        return [
            'inviteCode' => $this->inviteCode,
        ];
    }
}
