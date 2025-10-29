<?php

namespace euventura\UazapiSdk;

use Saloon\Http\Connector;

/**
 * UazAPI API Connector
 *
 * Connector Saloon para comunicação com a API UazAPI do WhatsApp.
 * Esta classe é responsável por configurar a URL base, headers de autenticação
 * e outras configurações necessárias para comunicação com a API.
 *
 * @package UazApi
 * @author UazAPI Team
 * @version 1.0.0
 *
 * @example
 * ```php
 * $connector = new UazapiApiConnector('https://free.uazapi.com', 'seu-token');
 * ```
 */
class UazapiApiConnector extends Connector
{
    /**
     * URL do servidor UazAPI
     *
     * @var string
     */
    protected string $server;

    /**
     * Token de autenticação da instância
     *
     * @var string
     */
    protected string $token;

    /**
     * Indica se é um token de administrador
     *
     * @var bool
     */
    protected bool $isAdmin;

    /**
     * Construtor do connector
     *
     * @param string $server URL do servidor UazAPI (ex: https://free.uazapi.com)
     * @param string $token Token de autenticação da instância
     * @param bool $isAdmin Define se o token é de administrador (padrão: false)
     *
     * @example
     * ```php
     * // Token de instância normal
     * $connector = new UazapiApiConnector('https://free.uazapi.com', 'instance-token');
     *
     * // Token de administrador
     * $connector = new UazapiApiConnector('https://free.uazapi.com', 'admin-token', true);
     * ```
     */
    public function __construct(string $server, string $token, bool $isAdmin = false)
    {
        $this->server = $server;
        $this->token = $token;
        $this->isAdmin = $isAdmin;
    }

    /**
     * Resolve a URL base para as requisições
     *
     * Retorna a URL do servidor configurada no construtor.
     * Todas as requisições serão feitas para endpoints relativos a esta URL.
     *
     * @return string URL base do servidor
     */
    public function resolveBaseUrl(): string
    {
        return $this->server;
    }

    /**
     * Define os headers padrão para todas as requisições
     *
     * Configura os headers de autenticação e content-type que serão
     * enviados em todas as requisições à API.
     *
     * @return array<string, string> Array associativo com os headers
     */
    public function defaultHeaders(): array
    {
        return [
            $this->isAdmin ? 'admintoken' : 'token' => $this->token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }
}
