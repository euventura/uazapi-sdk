<?php

namespace euventura\UazapiSdk\Requests\Group;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List Groups Request
 *
 * Retorna uma lista com todos os grupos disponíveis para a instância.
 *
 * @package UazApi\Requests\Group
 */
class ListGroupsRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param bool|null $force Forçar atualização do cache de grupos
     * @param bool|null $noparticipants Retornar grupos sem incluir os participantes
     */
    public function __construct(
        protected ?bool $force = null,
        protected ?bool $noparticipants = null
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/group/list';
    }

    protected function defaultQuery(): array
    {
        $query = [];

        if ($this->force !== null) {
            $query['force'] = $this->force ? 'true' : 'false';
        }

        if ($this->noparticipants !== null) {
            $query['noparticipants'] = $this->noparticipants ? 'true' : 'false';
        }

        return $query;
    }
}
