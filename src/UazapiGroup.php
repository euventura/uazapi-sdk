<?php

namespace euventura\UazapiSdk;

use euventura\UazapiSdk\Requests\Community\CreateCommunityRequest;
use euventura\UazapiSdk\Requests\Community\EditCommunityGroupsRequest;
use euventura\UazapiSdk\Requests\Group\CreateGroupRequest;
use euventura\UazapiSdk\Requests\Group\GetGroupInfoRequest;
use euventura\UazapiSdk\Requests\Group\GetInviteInfoRequest;
use euventura\UazapiSdk\Requests\Group\GetInviteLinkRequest;
use euventura\UazapiSdk\Requests\Group\JoinGroupRequest;
use euventura\UazapiSdk\Requests\Group\LeaveGroupRequest;
use euventura\UazapiSdk\Requests\Group\ListGroupsRequest;
use euventura\UazapiSdk\Requests\Group\ResetInviteCodeRequest;
use euventura\UazapiSdk\Requests\Group\UpdateAnnounceRequest;
use euventura\UazapiSdk\Requests\Group\UpdateDescriptionRequest;
use euventura\UazapiSdk\Requests\Group\UpdateImageRequest;
use euventura\UazapiSdk\Requests\Group\UpdateLockedRequest;
use euventura\UazapiSdk\Requests\Group\UpdateNameRequest;
use euventura\UazapiSdk\Requests\Group\UpdateParticipantsRequest;

/**
 * UazAPI Group and Community Resource
 *
 * Classe para gerenciamento de grupos e comunidades do WhatsApp.
 * Permite criar, modificar, gerenciar participantes e configurar grupos e comunidades.
 *
 * @package UazApi
 * @author UazAPI Team
 * @version 1.0.0
 *
 * @example
 * ```php
 * $connector = new UazapiApiConnector('https://free.uazapi.com', 'seu-token');
 * $group = new UazapiGroup($connector);
 *
 * // Criar grupo
 * $group->create('Meu Grupo', ['5511999999999', '5511888888888']);
 *
 * // Obter informações
 * $group->info('120363153742561022@g.us');
 * ```
 */
class UazapiGroup extends UazapiResource
{
    /**
     * Criar um novo grupo
     *
     * Cria um novo grupo no WhatsApp com nome e lista de participantes.
     * O usuário que cria o grupo automaticamente se torna administrador.
     *
     * @param string $name Nome do grupo (1-25 caracteres)
     * @param array<string> $participants Lista de números dos participantes (formato internacional sem +)
     *
     * @return ?array Resposta com dados do grupo criado
     *
     * @example
     * ```php
     * // Criar grupo com um participante
     * $group->create('Meu Novo Grupo', ['5521987905995']);
     *
     * // Criar grupo com múltiplos participantes
     * $group->create('Equipe de Projeto', [
     *     '5521987905995',
     *     '5511912345678',
     *     '5519987654321'
     * ]);
     * ```
     */
    public function create(string $name, array $participants): ?array
    {
        return $this->send(new CreateGroupRequest($name, $participants));
    }

    /**
     * Obter informações detalhadas de um grupo
     *
     * Recupera informações completas de um grupo, incluindo participantes,
     * configurações e opcionalmente o link de convite.
     *
     * @param string $groupjid JID do grupo (ex: 120363153742561022@g.us)
     * @param bool|null $getInviteLink Recuperar link de convite do grupo
     * @param bool|null $getRequestsParticipants Recuperar lista de solicitações pendentes
     * @param bool|null $force Forçar atualização, ignorando cache
     *
     * @return ?array Resposta com informações detalhadas do grupo
     *
     * @example
     * ```php
     * // Obter informações básicas
     * $group->info('120363153742561022@g.us');
     *
     * // Obter com link de convite
     * $group->info('120363153742561022@g.us', getInviteLink: true);
     *
     * // Forçar atualização e obter solicitações pendentes
     * $group->info('120363153742561022@g.us',
     *     getRequestsParticipants: true,
     *     force: true
     * );
     * ```
     */
    public function info(
        string $groupjid,
        ?bool  $getInviteLink = null,
        ?bool  $getRequestsParticipants = null,
        ?bool  $force = null
    ): ?array
    {
        return $this->send(new GetGroupInfoRequest(
            $groupjid,
            $getInviteLink,
            $getRequestsParticipants,
            $force
        ));
    }

    /**
     * Obter informações de um grupo pelo código de convite
     *
     * Retorna informações detalhadas de um grupo usando um código de convite,
     * permitindo verificar detalhes antes de entrar no grupo.
     *
     * @param string $inviteCode Código de convite ou URL completa
     *
     * @return ?array Resposta com informações do grupo
     *
     * @example
     * ```php
     * // Usando código de convite
     * $group->inviteInfo('IYnl5Zg9bUcJD32rJrDzO7');
     *
     * // Usando URL completa
     * $group->inviteInfo('https://chat.whatsapp.com/IYnl5Zg9bUcJD32rJrDzO7');
     * ```
     */
    public function inviteInfo(string $inviteCode): ?array
    {
        return $this->send(new GetInviteInfoRequest($inviteCode));
    }

    /**
     * Gerar link de convite para um grupo
     *
     * Retorna o link de convite para o grupo especificado.
     * Requer que o usuário seja administrador do grupo.
     *
     * @param string $groupJID JID do grupo (ex: 120363153742561022@g.us)
     *
     * @return ?array Resposta com o link de convite
     *
     * @example
     * ```php
     * $result = $group->inviteLink('120363153742561022@g.us');
     * echo $result['inviteLink']; // https://chat.whatsapp.com/...
     * ```
     */
    public function inviteLink(string $groupJID): ?array
    {
        return $this->send(new GetInviteLinkRequest($groupJID));
    }

    /**
     * Entrar em um grupo usando código de convite
     *
     * Permite entrar em um grupo do WhatsApp usando um código de convite ou URL.
     *
     * @param string $inviteCode Código de convite ou URL completa
     *
     * @return ?array Resposta com dados da entrada no grupo
     *
     * @example
     * ```php
     * // Entrar usando código
     * $group->join('IYnl5Zg9bUcJD32rJrDzO7');
     *
     * // Entrar usando URL completa
     * $group->join('https://chat.whatsapp.com/IYnl5Zg9bUcJD32rJrDzO7');
     * ```
     */
    public function join(string $inviteCode): ?array
    {
        return $this->send(new JoinGroupRequest($inviteCode));
    }

    /**
     * Sair de um grupo
     *
     * Remove o usuário atual de um grupo específico.
     * Se for o último administrador, o grupo será dissolvido.
     *
     * @param string $groupjid JID do grupo (ex: 120363324255083289@g.us)
     *
     * @return ?array Resposta confirmando a saída
     *
     * @example
     * ```php
     * $group->leave('120363324255083289@g.us');
     * ```
     */
    public function leave(string $groupjid): ?array
    {
        return $this->send(new LeaveGroupRequest($groupjid));
    }

    /**
     * Listar todos os grupos
     *
     * Retorna uma lista com todos os grupos da instância atual.
     *
     * @param bool|null $force Forçar atualização do cache de grupos
     * @param bool|null $noparticipants Retornar grupos sem incluir os participantes
     *
     * @return ?array Resposta com a lista de grupos
     *
     * @example
     * ```php
     * // Listar todos os grupos
     * $groups = $group->list();
     *
     * // Forçar atualização
     * $groups = $group->list(force: true);
     *
     * // Listar sem participantes (mais rápido)
     * $groups = $group->list(noparticipants: true);
     * ```
     */
    public function list(?bool $force = null, ?bool $noparticipants = null): ?array
    {
        return $this->send(new ListGroupsRequest($force, $noparticipants));
    }

    /**
     * Resetar código de convite do grupo
     *
     * Gera um novo código de convite para o grupo, invalidando o anterior.
     * Requer que o usuário seja administrador do grupo.
     *
     * @param string $groupjid JID do grupo (ex: 120363308883996631@g.us)
     *
     * @return ?array Resposta com o novo link de convite
     *
     * @example
     * ```php
     * $result = $group->resetInviteCode('120363308883996631@g.us');
     * echo $result['InviteLink']; // Novo link
     * ```
     */
    public function resetInviteCode(string $groupjid): ?array
    {
        return $this->send(new ResetInviteCodeRequest($groupjid));
    }

    /**
     * Configurar permissões de envio de mensagens
     *
     * Define se apenas administradores podem enviar mensagens no grupo.
     *
     * @param string $groupjid JID do grupo (ex: 120363339858396166@g.us)
     * @param bool $announce true = apenas admins podem enviar, false = todos podem enviar
     *
     * @return ?array Resposta confirmando a atualização
     *
     * @example
     * ```php
     * // Apenas admins podem enviar (modo anúncio)
     * $group->updateAnnounce('120363339858396166@g.us', true);
     *
     * // Todos podem enviar (modo normal)
     * $group->updateAnnounce('120363339858396166@g.us', false);
     * ```
     */
    public function updateAnnounce(string $groupjid, bool $announce): ?array
    {
        return $this->send(new UpdateAnnounceRequest($groupjid, $announce));
    }

    /**
     * Atualizar descrição do grupo
     *
     * Altera a descrição (tópico) do grupo.
     * Requer que o usuário seja administrador do grupo.
     *
     * @param string $groupjid JID do grupo (ex: 120363339858396166@g.us)
     * @param string $description Nova descrição/tópico do grupo (máx. 512 caracteres)
     *
     * @return ?array Resposta confirmando a atualização
     *
     * @example
     * ```php
     * $group->updateDescription(
     *     '120363339858396166@g.us',
     *     'Grupo oficial de suporte técnico'
     * );
     * ```
     */
    public function updateDescription(string $groupjid, string $description): ?array
    {
        return $this->send(new UpdateDescriptionRequest($groupjid, $description));
    }

    /**
     * Atualizar imagem do grupo
     *
     * Altera a imagem do grupo. A imagem pode ser URL, base64 ou "remove" para remover.
     * Formato: JPEG, resolução máxima: 640x640 pixels.
     *
     * @param string $groupjid JID do grupo (ex: 120363308883996631@g.us)
     * @param string $image URL, base64 da imagem ou "remove"/"delete" para remover
     *
     * @return ?array Resposta confirmando a atualização
     *
     * @example
     * ```php
     * // Atualizar com URL
     * $group->updateImage('120363308883996631@g.us', 'https://exemplo.com/logo.jpg');
     *
     * // Atualizar com base64
     * $group->updateImage('120363308883996631@g.us', 'data:image/jpeg;base64,/9j/4AAQ...');
     *
     * // Remover imagem
     * $group->updateImage('120363308883996631@g.us', 'remove');
     * ```
     */
    public function updateImage(string $groupjid, string $image): ?array
    {
        return $this->send(new UpdateImageRequest($groupjid, $image));
    }

    /**
     * Configurar permissão de edição do grupo
     *
     * Define se apenas administradores podem editar informações do grupo
     * (nome, descrição, imagem, etc).
     *
     * @param string $groupjid JID do grupo (ex: 120363308883996631@g.us)
     * @param bool $locked true = apenas admins podem editar, false = todos podem editar
     *
     * @return ?array Resposta confirmando a atualização
     *
     * @example
     * ```php
     * // Apenas admins podem editar
     * $group->updateLocked('120363308883996631@g.us', true);
     *
     * // Todos podem editar
     * $group->updateLocked('120363308883996631@g.us', false);
     * ```
     */
    public function updateLocked(string $groupjid, bool $locked): ?array
    {
        return $this->send(new UpdateLockedRequest($groupjid, $locked));
    }

    /**
     * Atualizar nome do grupo
     *
     * Altera o nome de um grupo do WhatsApp.
     * Requer que o usuário seja administrador do grupo.
     *
     * @param string $groupjid JID do grupo (ex: 120363339858396166@g.us)
     * @param string $name Novo nome para o grupo (1-25 caracteres)
     *
     * @return ?array Resposta confirmando a atualização
     *
     * @example
     * ```php
     * $group->updateName('120363339858396166@g.us', 'Novo Nome do Grupo');
     * ```
     */
    public function updateName(string $groupjid, string $name): ?array
    {
        return $this->send(new UpdateNameRequest($groupjid, $name));
    }

    /**
     * Gerenciar participantes do grupo
     *
     * Adiciona, remove, promove ou rebaixa participantes do grupo.
     * Requer que o usuário seja administrador do grupo.
     *
     * Ações disponíveis:
     * - add: Adicionar participantes
     * - remove: Remover participantes
     * - promote: Promover a administrador
     * - demote: Remover privilégios de administrador
     * - approve: Aprovar solicitações pendentes
     * - reject: Rejeitar solicitações pendentes
     *
     * @param string $groupjid JID do grupo (ex: 120363308883996631@g.us)
     * @param string $action Ação: add, remove, promote, demote, approve, reject
     * @param array<string> $participants Lista de números ou JIDs dos participantes
     *
     * @return ?array Resposta com status da operação para cada participante
     *
     * @example
     * ```php
     * // Adicionar participantes
     * $group->updateParticipants('120363308883996631@g.us', 'add', [
     *     '5521987654321',
     *     '5511999887766'
     * ]);
     *
     * // Promover a administradores
     * $group->updateParticipants('120363308883996631@g.us', 'promote', [
     *     '5521987654321'
     * ]);
     *
     * // Remover participantes
     * $group->updateParticipants('120363308883996631@g.us', 'remove', [
     *     '5511999887766'
     * ]);
     * ```
     */
    public function updateParticipants(string $groupjid, string $action, array $participants): ?array
    {
        return $this->send(new UpdateParticipantsRequest($groupjid, $action, $participants));
    }

    /**
     * Criar uma comunidade
     *
     * Cria uma nova comunidade no WhatsApp.
     * Uma comunidade permite agrupar múltiplos grupos relacionados.
     * O usuário que cria torna-se automaticamente administrador.
     *
     * @param string $name Nome da comunidade
     *
     * @return ?array Resposta com dados da comunidade criada
     *
     * @example
     * ```php
     * $community = $group->createCommunity('Comunidade do Bairro');
     * ```
     */
    public function createCommunity(string $name): ?array
    {
        return $this->send(new CreateCommunityRequest($name));
    }

    /**
     * Gerenciar grupos em uma comunidade
     *
     * Adiciona ou remove grupos de uma comunidade.
     * Requer que o usuário seja administrador da comunidade.
     *
     * @param string $community JID da comunidade (ex: 120363153742561022@g.us)
     * @param string $action Ação: add ou remove
     * @param array<string> $groupjids Lista de JIDs dos grupos
     *
     * @return ?array Resposta com lista de grupos processados (sucesso e falhas)
     *
     * @example
     * ```php
     * // Adicionar grupos à comunidade
     * $group->editCommunityGroups('120363153742561022@g.us', 'add', [
     *     '120363324255083289@g.us',
     *     '120363308883996631@g.us'
     * ]);
     *
     * // Remover grupos da comunidade
     * $group->editCommunityGroups('120363153742561022@g.us', 'remove', [
     *     '120363308883996631@g.us'
     * ]);
     * ```
     */
    public function editCommunityGroups(string $community, string $action, array $groupjids): ?array
    {
        return $this->send(new EditCommunityGroupsRequest($community, $action, $groupjids));
    }
}
