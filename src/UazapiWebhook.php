<?php

namespace euventura\UazapiSdk;

use euventura\UazapiSdk\Requests\Webhook\ConfigureWebhookRequest;
use euventura\UazapiSdk\Requests\Webhook\GetWebhookRequest;

/**
 * UazAPI Webhook Resource
 *
 * Classe para configuração e gerenciamento de webhooks.
 * Permite receber eventos da API em tempo real através de callbacks HTTP.
 *
 * Modos de uso:
 * - Modo Simples: Um webhook por instância (recomendado)
 * - Modo Avançado: Múltiplos webhooks por instância
 *
 * @package UazApi
 * @author UazAPI Team
 * @version 1.0.0
 *
 * @example
 * ```php
 * $connector = new UazapiApiConnector('https://free.uazapi.com', 'seu-token');
 * $webhook = new UazapiWebhook($connector);
 *
 * // Configurar webhook
 * $webhook->configure(
 *     'https://meusite.com/webhook',
 *     ['messages', 'connection'],
 *     excludeMessages: ['wasSentByApi']
 * );
 * ```
 */
class UazapiWebhook extends UazapiResource
{
    /**
     * Ver configuração atual do webhook
     *
     * Retorna a lista de webhooks configurados para a instância,
     * incluindo URL, eventos ativos, filtros e outras configurações.
     *
     * @return ?arrayResposta com array de webhooks configurados
     *
     * @example
     * ```php
     * $response = $webhook->get();
     *
     * if ($response->successful()) {
     *     $webhooks = $response->json();
     *     foreach ($webhooks as $wh) {
     *         echo "URL: " . $wh['url'];
     *         echo "Eventos: " . implode(', ', $wh['events']);
     *     }
     * }
     * ```
     */
    public function get(): ?array
    {
        return $this->send(new GetWebhookRequest());
    }

    /**
     * Configurar webhook (modo simples - recomendado)
     *
     * Configura um webhook para a instância. Se já existir um webhook,
     * ele será atualizado. Se não existir, será criado automaticamente.
     *
     * Este é o método recomendado para a maioria dos casos de uso.
     *
     * Eventos disponíveis:
     * - connection: Alterações no estado da conexão
     * - history: Recebimento de histórico
     * - messages: Novas mensagens
     * - messages_update: Atualizações em mensagens
     * - call: Eventos de chamadas
     * - contacts: Atualizações de contatos
     * - presence: Status de presença
     * - groups: Modificações em grupos
     * - labels: Gerenciamento de etiquetas
     * - chats: Eventos de conversas
     * - chat_labels: Etiquetas de conversas
     * - blocks: Bloqueios/desbloqueios
     * - leads: Atualizações de leads
     *
     * Filtros de exclusão (excludeMessages):
     * - wasSentByApi: Mensagens enviadas pela API (recomendado para evitar loops!)
     * - wasNotSentByApi: Mensagens não enviadas pela API
     * - fromMeYes: Mensagens enviadas pelo usuário
     * - fromMeNo: Mensagens recebidas de terceiros
     * - isGroupYes: Mensagens em grupos
     * - isGroupNo: Mensagens em conversas individuais
     *
     * @param string $url URL do webhook para receber os eventos
     * @param array<string> $events Lista de eventos a monitorar
     * @param bool $enabled Ativar/desativar webhook (padrão: true)
     * @param array<string>|null $excludeMessages Filtros de exclusão (recomendado: ['wasSentByApi'])
     * @param bool $addUrlEvents Se true, adiciona o nome do evento na URL (/webhook/messages)
     * @param bool $addUrlTypesMessages Se true, adiciona o tipo de mensagem na URL
     *
     * @return ?arrayResposta confirmando a configuração
     *
     * @example
     * ```php
     * // Configuração básica (recomendado)
     * $webhook->configure(
     *     'https://meusite.com/webhook',
     *     ['messages', 'connection'],
     *     excludeMessages: ['wasSentByApi']
     * );
     *
     * // Configuração avançada
     * $webhook->configure(
     *     url: 'https://meusite.com/webhook',
     *     events: ['messages', 'messages_update', 'connection'],
     *     enabled: true,
     *     excludeMessages: ['wasSentByApi', 'isGroupYes'],
     *     addUrlEvents: true,
     *     addUrlTypesMessages: true
     * );
     * ```
     */
    public function configure(
        string $url,
        array  $events,
        bool   $enabled = true,
        ?array $excludeMessages = null,
        bool   $addUrlEvents = false,
        bool   $addUrlTypesMessages = false
    ): ?array
    {
        return $this->send(new ConfigureWebhookRequest(
            url: $url,
            events: $events,
            enabled: $enabled,
            excludeMessages: $excludeMessages,
            addUrlEvents: $addUrlEvents,
            addUrlTypesMessages: $addUrlTypesMessages
        ));
    }

    /**
     * Criar novo webhook (modo avançado)
     *
     * Adiciona um novo webhook à instância sem modificar webhooks existentes.
     * Use este método apenas se precisar de múltiplos webhooks por instância.
     *
     * Nota: Para a maioria dos casos, use configure() que é mais simples.
     *
     * @param string $url URL do webhook
     * @param array<string> $events Lista de eventos
     * @param bool $enabled Ativar/desativar webhook
     * @param array<string>|null $excludeMessages Filtros de exclusão
     * @param bool $addUrlEvents Adicionar evento na URL
     * @param bool $addUrlTypesMessages Adicionar tipo de mensagem na URL
     *
     * @return ?arrayResposta com o webhook criado incluindo seu ID
     *
     * @example
     * ```php
     * $response = $webhook->add(
     *     'https://outro-site.com/webhook',
     *     ['presence', 'groups']
     * );
     *
     * if ($response->successful()) {
     *     $data = $response->json();
     *     echo "Webhook criado com ID: " . $data['id'];
     * }
     * ```
     */
    public function add(
        string $url,
        array  $events,
        bool   $enabled = true,
        ?array $excludeMessages = null,
        bool   $addUrlEvents = false,
        bool   $addUrlTypesMessages = false
    ): ?array
    {
        return $this->send(new ConfigureWebhookRequest(
            url: $url,
            events: $events,
            enabled: $enabled,
            excludeMessages: $excludeMessages,
            addUrlEvents: $addUrlEvents,
            addUrlTypesMessages: $addUrlTypesMessages,
            action: 'add'
        ));
    }

    /**
     * Atualizar webhook existente (modo avançado)
     *
     * Atualiza um webhook específico pelo seu ID.
     * Todos os campos do webhook serão atualizados com os novos valores.
     *
     * Para obter o ID do webhook, use o método get().
     *
     * @param string $id ID do webhook a ser atualizado
     * @param string $url Nova URL do webhook
     * @param array<string> $events Nova lista de eventos
     * @param bool $enabled Ativar/desativar webhook
     * @param array<string>|null $excludeMessages Novos filtros de exclusão
     * @param bool $addUrlEvents Adicionar evento na URL
     * @param bool $addUrlTypesMessages Adicionar tipo de mensagem na URL
     *
     * @return ?arrayResposta com o webhook atualizado
     *
     * @example
     * ```php
     * $webhook->update(
     *     id: 'webhook-id-123',
     *     url: 'https://site-atualizado.com/webhook',
     *     events: ['messages', 'connection']
     * );
     * ```
     */
    public function update(
        string $id,
        string $url,
        array  $events,
        bool   $enabled = true,
        ?array $excludeMessages = null,
        bool   $addUrlEvents = false,
        bool   $addUrlTypesMessages = false
    ): ?array
    {
        return $this->send(new ConfigureWebhookRequest(
            url: $url,
            events: $events,
            enabled: $enabled,
            excludeMessages: $excludeMessages,
            addUrlEvents: $addUrlEvents,
            addUrlTypesMessages: $addUrlTypesMessages,
            action: 'update',
            id: $id
        ));
    }

    /**
     * Deletar webhook (modo avançado)
     *
     * Remove um webhook específico pelo seu ID.
     * Após a remoção, o webhook não receberá mais eventos.
     *
     * Para obter o ID do webhook, use o método get().
     *
     * @param string $id ID do webhook a ser removido
     *
     * @return ?arrayResposta confirmando a remoção
     *
     * @example
     * ```php
     * $response = $webhook->delete('webhook-id-123');
     *
     * if ($response->successful()) {
     *     echo "Webhook removido com sucesso!";
     * }
     * ```
     */
    public function delete(string $id): ?array
    {
        return $this->send(new ConfigureWebhookRequest(
            url: '',
            events: [],
            action: 'delete',
            id: $id
        ));
    }
}

