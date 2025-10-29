<?php

namespace euventura\UazapiSdk\Requests\QuickReply;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class EditQuickReplyRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string  $shortCut,
        protected string  $type,
        protected ?string $id = null,
        protected ?bool   $delete = false,
        protected ?string $text = null,
        protected ?string $file = null,
        protected ?string $docName = null
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/quickreply/edit';
    }

    public function defaultBody(): array
    {
        $body = [
            'shortCut' => $this->shortCut,
            'type' => $this->type,
        ];

        if ($this->id !== null) {
            $body['id'] = $this->id;
        }

        if ($this->delete !== false) {
            $body['delete'] = $this->delete;
        }

        if ($this->text !== null) {
            $body['text'] = $this->text;
        }

        if ($this->file !== null) {
            $body['file'] = $this->file;
        }

        if ($this->docName !== null) {
            $body['docName'] = $this->docName;
        }

        return $body;
    }
}

