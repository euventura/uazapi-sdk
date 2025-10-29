<?php

namespace euventura\UazapiSdk\Requests\QuickReply;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetAllQuickRepliesRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/quickreply/showall';
    }
}

