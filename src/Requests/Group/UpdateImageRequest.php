<?php

namespace euventura\UazapiSdk\Requests\Group;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update Image Request
 *
 * Altera a imagem do grupo. A imagem pode ser URL, base64 ou "remove"/"delete" para remover.
 *
 * @package UazApi\Requests\Group
 */
class UpdateImageRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param string $groupjid JID do grupo (ex: 120363308883996631@g.us)
     * @param string $image URL, base64 da imagem ou "remove"/"delete" para remover (JPEG, máx. 640x640)
     */
    public function __construct(
        protected string $groupjid,
        protected string $image
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/group/updateImage';
    }

    public function defaultBody(): array
    {
        return [
            'groupjid' => $this->groupjid,
            'image' => $this->image,
        ];
    }
}
