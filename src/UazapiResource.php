<?php

namespace UazApi;

use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Throwable;

/**
 * UazAPI Resource Base Class
 *
 * Classe base abstrata para todos os resources do SDK.
 * Fornece funcionalidade comum para enviar requisições através do connector Saloon.
 *
 * Todas as classes de resource (Instance, Message, Profile, etc) herdam desta classe
 * e têm acesso ao método send() para executar requisições na API.
 *
 * @package UazApi
 * @author UazAPI Team
 * @version 1.0.0
 *
 * @abstract
 */
abstract class UazapiResource
{
    /**
     * Instância do connector Saloon
     *
     * @var Connector
     */
    protected Connector $connector;

    /**
     * Construtor do resource
     *
     * Inicializa o resource com uma instância do connector para
     * comunicação com a API.
     *
     * @param Connector $connector Instância do connector Saloon configurado
     *
     * @example
     * ```php
     * class MyResource extends UazapiResource {
     *     // A classe filha recebe o connector automaticamente
     * }
     * ```
     */
    public function __construct(Connector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * Envia uma requisição à API
     *
     * Método helper que simplifica o envio de requisições usando o connector.
     * Este método é usado internamente por todos os métodos dos resources.
     *
     * @param Request $request Objeto de requisição Saloon a ser enviado
     *
     * @return Response|Throwable Objeto de resposta Saloon com os dados retornados
     *
     * @throws FatalRequestException
     * @throws RequestException
     * @example
     * ```php
     * // Usado internamente pelos resources
     * protected function myMethod() {
     *     return $this->send(new MyRequest());
     * }
     * ```
     */
    public function send(Request $request): ?array
    {
        try {
            $response = $this->connector->send($request);
            if ($response->successful()) {
                return $response->json();
            }
            return $response->json() ?? ['error' => 'Request failed', 'status' => $response->status()];
        } catch (Throwable $e) {
            return ['error' => $e->getMessage()];
        }
    }
}