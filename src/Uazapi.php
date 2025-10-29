<?php

namespace euventura\UazapiSdk;

use Saloon\Http\Connector;

/**
 * UazAPI SDK - Classe principal para acesso aos recursos da API
 *
 * Esta é a classe principal do SDK que fornece acesso a todos os recursos
 * da API UazAPI do WhatsApp. Ela inicializa o connector e fornece métodos
 * factory para acessar as diferentes categorias de recursos.
 *
 * @package UazApi
 * @author UazAPI Team
 * @version 1.0.0
 *
 * @example
 * ```php
 * $uazapi = new Uazapi('https://free.uazapi.com', 'seu-token');
 *
 * // Acessar recursos
 * $uazapi->instance()->status();
 * $uazapi->message()->sendText('5511999999999', 'Olá!');
 * ```
 */
class Uazapi
{
    /**
     * Instância do connector Saloon para comunicação com a API
     *
     * @var Connector
     */
    public Connector $connector;

    /**
     * Construtor do SDK UazAPI
     *
     * Inicializa o SDK com o servidor e token de autenticação.
     * O connector é criado automaticamente e fica disponível publicamente
     * caso seja necessário acesso direto.
     *
     * @param string $server URL do servidor UazAPI (ex: https://free.uazapi.com)
     * @param string $token Token de autenticação da instância
     *
     * @example
     * ```php
     * $uazapi = new Uazapi('https://free.uazapi.com', 'abc123xyz');
     * ```
     */
    public function __construct(protected string $server, protected string $token)
    {
        $this->connector = new UazapiApiConnector($this->server, $this->token);
    }

    /**
     * Retorna a instância do resource de gerenciamento de instância
     *
     * Fornece acesso aos métodos de gerenciamento do ciclo de vida da instância,
     * como conectar, desconectar, verificar status, atualizar configurações, etc.
     *
     * @return UazapiInstance
     *
     * @example
     * ```php
     * $uazapi->instance()->connect();
     * $uazapi->instance()->status();
     * $uazapi->instance()->disconnect();
     * ```
     */
    public function instance(): UazapiInstance
    {
        return new UazapiInstance($this->connector);
    }

    /**
     * Retorna a instância do resource de envio de mensagens
     *
     * Fornece acesso aos métodos de envio de diferentes tipos de mensagens,
     * incluindo texto, imagem, vídeo, áudio, documento, localização, etc.
     *
     * @return UazapiMessage
     *
     * @example
     * ```php
     * $uazapi->message()->sendText('5511999999999', 'Olá!');
     * $uazapi->message()->sendImage('5511999999999', 'https://exemplo.com/img.jpg');
     * ```
     */
    public function message(): UazapiMessage
    {
        return new UazapiMessage($this->connector);
    }

    /**
     * Retorna a instância do resource de perfil do WhatsApp
     *
     * Fornece acesso aos métodos de gerenciamento do perfil da conta WhatsApp,
     * como alterar nome e foto de perfil.
     *
     * @return UazapiProfile
     *
     * @example
     * ```php
     * $uazapi->profile()->updateName('Minha Loja');
     * $uazapi->profile()->updateImage('https://exemplo.com/logo.jpg');
     * ```
     */
    public function profile(): UazapiProfile
    {
        return new UazapiProfile($this->connector);
    }

    /**
     * Retorna a instância do resource de respostas rápidas
     *
     * Fornece acesso aos métodos de gerenciamento de respostas rápidas,
     * permitindo criar, editar, deletar e listar templates de mensagens.
     *
     * @return UazapiQuickReply
     *
     * @example
     * ```php
     * $uazapi->quickReply()->createText('saudacao', 'Olá! Como posso ajudar?');
     * $uazapi->quickReply()->getAll();
     * ```
     */
    public function quickReply(): UazapiQuickReply
    {
        return new UazapiQuickReply($this->connector);
    }

    /**
     * Retorna a instância do resource de webhooks
     *
     * Fornece acesso aos métodos de configuração de webhooks para
     * receber eventos em tempo real da API.
     *
     * @return UazapiWebhook
     *
     * @example
     * ```php
     * $uazapi->webhook()->configure('https://site.com/webhook', ['messages']);
     * $uazapi->webhook()->get();
     * ```
     */
    public function webhook(): UazapiWebhook
    {
        return new UazapiWebhook($this->connector);
    }
}