<?php

namespace euventura\UazapiSdk\Resources;

use euventura\UazapiSdk\arrayResposta;
use euventura\UazapiSdk\Requests\Instance\ConnectRequest;
use euventura\UazapiSdk\Requests\Instance\DeleteInstanceRequest;
use euventura\UazapiSdk\Requests\Instance\DisconnectRequest;
use euventura\UazapiSdk\Requests\Instance\GetPrivacyRequest;
use euventura\UazapiSdk\Requests\Instance\StatusRequest;
use euventura\UazapiSdk\Requests\Instance\UpdateInstanceNameRequest;
use euventura\UazapiSdk\Requests\Instance\UpdatePrivacyRequest;

/**
 * UazAPI Instance Resource
 *
 * Classe para gerenciamento do ciclo de vida de uma instância do WhatsApp.
 * Fornece métodos para conectar, desconectar, verificar status, atualizar
 * configurações e gerenciar a privacidade da instância.
 *
 * @package UazApi
 * @author UazAPI Team
 * @version 1.0.0
 *
 * @example
 * ```php
 * $connector = new UazapiApiConnector('https://free.uazapi.com', 'seu-token');
 * $instance = new UazapiInstance($connector);
 *
 * // Conectar ao WhatsApp
 * $instance->connect('5511999999999');
 *
 * // Verificar status
 * $status = $instance->status();
 * ```
 */
class UazapiInstance extends UazapiResource
{
    /**
     * Conectar instância ao WhatsApp
     *
     * Inicia o processo de conexão da instância ao WhatsApp.
     * Se nenhum telefone for fornecido, gera um QR Code para escaneamento.
     * Se um telefone for fornecido, gera um código de pareamento.
     *
     * Estados possíveis após conexão:
     * - disconnected: Desconectado do WhatsApp
     * - connecting: Em processo de conexão (aguardando QR Code ou pareamento)
     * - connected: Conectado e autenticado com sucesso
     *
     * @param string|null $phone Número de telefone no formato internacional (ex: 5511999999999)
     *                           Se não fornecido, gera QR Code. Se fornecido, gera código de pareamento.
     *
     * @return ?arrayResposta com status da conexão, QR Code ou código de pareamento
     *
     * @example
     * ```php
     * // Conectar com QR Code
     * $response = $instance->connect();
     *
     * // Conectar com código de pareamento
     * $response = $instance->connect('5511999999999');
     *
     * if ($response->successful()) {
     *     $data = $response->json();
     *     echo "QR Code: " . $data['instance']['qrcode'];
     *     echo "Código de Pareamento: " . $data['instance']['paircode'];
     * }
     * ```
     */
    public function connect(?string $phone = null): ?array
    {
        return $this->send(new ConnectRequest($phone));
    }

    /**
     * Desconectar instância do WhatsApp
     *
     * Encerra a sessão ativa do WhatsApp, desconectando a instância.
     * Após desconectar, será necessário um novo QR Code ou código de
     * pareamento para reconectar.
     *
     * Diferença entre desconectar e deletar:
     * - Desconectar: Mantém a instância, apenas encerra a sessão
     * - Deletar: Remove completamente a instância do sistema
     *
     * @return ?arrayResposta confirmando a desconexão
     *
     * @example
     * ```php
     * $response = $instance->disconnect();
     *
     * if ($response->successful()) {
     *     echo "Instância desconectada com sucesso!";
     * }
     * ```
     */
    public function disconnect(): ?array
    {
        return $this->send(new DisconnectRequest());
    }

    /**
     * Verificar status da instância
     *
     * Retorna o status atual da instância, incluindo estado da conexão,
     * QR Code atualizado (se em processo de conexão), código de pareamento,
     * informações do perfil e dados da última desconexão.
     *
     * Este método é útil para:
     * - Monitorar o progresso da conexão
     * - Obter QR Codes atualizados durante o processo de conexão
     * - Verificar se a instância está online
     * - Identificar problemas de conexão
     *
     * @return ?arrayResposta com status completo da instância
     *
     * @example
     * ```php
     * $response = $instance->status();
     *
     * if ($response->successful()) {
     *     $data = $response->json();
     *     echo "Status: " . $data['instance']['status'];
     *     echo "Conectado: " . ($data['status']['connected'] ? 'Sim' : 'Não');
     *     echo "Nome do Perfil: " . $data['instance']['profileName'];
     * }
     * ```
     */
    public function status(): ?array
    {
        return $this->send(new StatusRequest());
    }

    /**
     * Deletar instância
     *
     * Remove completamente a instância do sistema, incluindo:
     * - Desconexão do WhatsApp
     * - Remoção de todos os dados associados
     * - Invalidação do token
     *
     * ATENÇÃO: Esta ação é irreversível! Após deletar, será necessário
     * criar uma nova instância para usar a API novamente.
     *
     * @return ?arrayResposta confirmando a exclusão
     *
     * @example
     * ```php
     * $response = $instance->delete();
     *
     * if ($response->successful()) {
     *     echo "Instância deletada permanentemente!";
     * }
     * ```
     */
    public function delete(): ?array
    {
        return $this->send(new DeleteInstanceRequest());
    }

    /**
     * Atualizar nome da instância
     *
     * Altera o nome identificador da instância no sistema.
     * Este nome é usado apenas internamente para identificação,
     * não afeta o nome do perfil do WhatsApp.
     *
     * Para alterar o nome do perfil do WhatsApp, use:
     * UazapiProfile::updateName()
     *
     * @param string $name Novo nome para a instância (pode conter espaços e caracteres especiais)
     *
     * @return ?arrayResposta com dados atualizados da instância
     *
     * @example
     * ```php
     * $response = $instance->updateName('Minha Loja - Produção');
     *
     * if ($response->successful()) {
     *     echo "Nome da instância atualizado!";
     * }
     * ```
     */
    public function updateName(string $name): ?array
    {
        return $this->send(new UpdateInstanceNameRequest($name));
    }

    /**
     * Buscar configurações de privacidade
     *
     * Retorna as configurações atuais de privacidade da conta WhatsApp,
     * incluindo quem pode:
     * - Ver visto por último
     * - Ver status (recado embaixo do nome)
     * - Ver foto de perfil
     * - Adicionar a grupos
     * - Ver status online
     * - Fazer chamadas
     * - Receber confirmação de leitura
     *
     * @return ?arrayResposta com todas as configurações de privacidade
     *
     * @example
     * ```php
     * $response = $instance->getPrivacy();
     *
     * if ($response->successful()) {
     *     $privacy = $response->json();
     *     echo "Último visto: " . $privacy['last'];
     *     echo "Foto de perfil: " . $privacy['profile'];
     *     echo "Status: " . $privacy['status'];
     * }
     * ```
     */
    public function getPrivacy(): ?array
    {
        return $this->send(new GetPrivacyRequest());
    }

    /**
     * Atualizar configurações de privacidade
     *
     * Altera uma ou múltiplas configurações de privacidade da conta WhatsApp.
     * Apenas as configurações enviadas serão alteradas, as demais permanecem inalteradas.
     *
     * Configurações disponíveis:
     * - groupadd: Quem pode adicionar a grupos (all, contacts, contact_blacklist, none)
     * - last: Quem pode ver visto por último (all, contacts, contact_blacklist, none)
     * - status: Quem pode ver status/recado (all, contacts, contact_blacklist, none)
     * - profile: Quem pode ver foto de perfil (all, contacts, contact_blacklist, none)
     * - readreceipts: Confirmação de leitura (all, none)
     * - online: Quem pode ver status online (all, match_last_seen)
     * - calladd: Quem pode fazer chamadas (all, known)
     *
     * @param array<string, string> $privacySettings Array associativo com as configurações
     *
     * @return ?arrayResposta com as configurações atualizadas
     *
     * @example
     * ```php
     * // Atualizar uma configuração
     * $instance->updatePrivacy(['groupadd' => 'contacts']);
     *
     * // Atualizar múltiplas configurações
     * $response = $instance->updatePrivacy([
     *     'groupadd' => 'contacts',
     *     'last' => 'none',
     *     'status' => 'contacts',
     *     'profile' => 'contacts',
     *     'readreceipts' => 'all'
     * ]);
     *
     * if ($response->successful()) {
     *     echo "Privacidade atualizada!";
     * }
     * ```
     */
    public function updatePrivacy(array $privacySettings): ?array
    {
        return $this->send(new UpdatePrivacyRequest($privacySettings));
    }
}