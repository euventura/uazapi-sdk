# UazAPI SDK - Guia de Uso Completo

Este documento explica como usar o UazAPI SDK de forma completa.

## 🚀 Inicialização

```php
use euventura\UazapiSdk\Uazapi;

// Inicializar o SDK
$uazapi = new Uazapi('https://free.uazapi.com', 'seu-token-aqui');
```

## 📋 Recursos Disponíveis

O SDK oferece acesso aos seguintes recursos:

- **instance()** - Gerenciamento de instância
- **profile()** - Perfil do WhatsApp
- **webhook()** - Configuração de webhooks
- **message()** - Envio de mensagens e stories
- **quickReply()** - Respostas rápidas
- **groups()** - Grupos e comunidades

---

## 📱 Instance - Gerenciamento de Instância

Gerenciamento do ciclo de vida da instância do WhatsApp.

### Métodos Disponíveis

```php
// Conectar ao WhatsApp com QR Code
$response = $uazapi->instance()->connect();

// Conectar com código de pareamento
$response = $uazapi->instance()->connect('5511999999999');

// Desconectar
$response = $uazapi->instance()->disconnect();

// Verificar status
$response = $uazapi->instance()->status();

// Deletar instância
$response = $uazapi->instance()->delete();

// Atualizar nome da instância
$response = $uazapi->instance()->updateName('Minha Nova Instância');

// Buscar configurações de privacidade
$response = $uazapi->instance()->getPrivacy();

// Atualizar configurações de privacidade
$response = $uazapi->instance()->updatePrivacy([
    'groupadd' => 'contacts',
    'last' => 'none',
    'status' => 'contacts',
    'profile' => 'contacts'
]);
```

---

## 👤 Profile - Gerenciamento de Perfil

Gerenciamento do perfil do WhatsApp.

### Métodos Disponíveis

```php
// Alterar nome do perfil
$response = $uazapi->profile()->updateName('Minha Loja');

// Alterar foto do perfil (URL)
$response = $uazapi->profile()->updateImage('https://exemplo.com/foto.jpg');

// Alterar foto do perfil (base64)
$response = $uazapi->profile()->updateImage('data:image/jpeg;base64,/9j/4AAQ...');

// Remover foto do perfil
$response = $uazapi->profile()->removeImage();
```

---

## 🔗 Webhook - Configuração de Webhooks

Configuração de webhooks para receber eventos em tempo real.

### Métodos Disponíveis

```php
// Ver configuração atual
$response = $uazapi->webhook()->get();

// Configurar webhook (modo simples - recomendado)
$response = $uazapi->webhook()->configure(
    url: 'https://meusite.com/webhook',
    events: ['messages', 'connection'],
    enabled: true,
    excludeMessages: ['wasSentByApi'] // Importante para evitar loops
);

// Configurar com opções avançadas
$response = $uazapi->webhook()->configure(
    url: 'https://meusite.com/webhook',
    events: ['messages', 'messages_update', 'connection'],
    enabled: true,
    excludeMessages: ['wasSentByApi', 'isGroupYes'],
    addUrlEvents: true,
    addUrlTypesMessages: true
);

// === Modo Avançado (múltiplos webhooks) ===

// Adicionar novo webhook
$response = $uazapi->webhook()->add(
    url: 'https://outro-site.com/webhook',
    events: ['presence', 'groups']
);

// Atualizar webhook existente
$response = $uazapi->webhook()->update(
    id: 'webhook-id-123',
    url: 'https://site-atualizado.com/webhook',
    events: ['messages']
);

// Deletar webhook
$response = $uazapi->webhook()->delete('webhook-id-123');
```

### Eventos Disponíveis

- `connection` - Alterações no estado da conexão
- `history` - Recebimento de histórico
- `messages` - Novas mensagens
- `messages_update` - Atualizações em mensagens
- `call` - Eventos de chamadas
- `contacts` - Atualizações de contatos
- `presence` - Status de presença
- `groups` - Modificações em grupos
- `labels` - Gerenciamento de etiquetas
- `chats` - Eventos de conversas
- `chat_labels` - Etiquetas de conversas
- `blocks` - Bloqueios/desbloqueios
- `leads` - Atualizações de leads

---

## 💬 Message - Envio de Mensagens

Envio de mensagens de diferentes tipos e stories/status.

### Mensagens de Texto

```php
// Texto simples
$response = $uazapi->message()->sendText('5511999999999', 'Olá! Como posso ajudar?');

// Texto com preview de link
$response = $uazapi->message()->sendText('5511999999999', 'Confira: https://exemplo.com',
    linkPreview: true,
    messageDelay: 2000
);

// Texto com preview personalizado
$response = $uazapi->message()->sendText(
    '5511999999999',
    'https://exemplo.com',
    linkPreview: true,
    linkPreviewTitle: 'Título Custom',
    linkPreviewDescription: 'Descrição custom',
    linkPreviewImage: 'https://exemplo.com/thumb.jpg',
    linkPreviewLarge: true
);

// Respondendo mensagem
$response = $uazapi->message()->sendText(
    '5511999999999',
    'Respondendo!',
    replyid: '3EB0538DA65A59F6D8A251'
);

// Mencionando usuários em grupo
$response = $uazapi->message()->sendText(
    '120363012345678901@g.us',
    'Olá @todos!',
    mentions: 'all'
);
```

### Imagens

```php
// Imagem simples
$response = $uazapi->message()->sendImage('5511999999999', 'https://exemplo.com/foto.jpg');

// Imagem com legenda
$response = $uazapi->message()->sendImage(
    '5511999999999',
    'https://exemplo.com/foto.jpg',
    'Veja esta foto!'
);

// Imagem com opções
$response = $uazapi->message()->sendImage(
    '5511999999999',
    'https://exemplo.com/foto.jpg',
    'Promoção!',
    ['delay' => 3000, 'forward' => true]
);
```

### Vídeos

```php
// Vídeo simples
$response = $uazapi->message()->sendVideo('5511999999999', 'https://exemplo.com/video.mp4');

// Vídeo com legenda
$response = $uazapi->message()->sendVideo(
    '5511999999999',
    'https://exemplo.com/video.mp4',
    'Confira!'
);
```

### Documentos

```php
// Documento
$response = $uazapi->message()->sendDocument(
    '5511999999999',
    'https://exemplo.com/contrato.pdf',
    'Contrato.pdf',
    'Segue o documento'
);
```

### Áudios

```php
// Áudio comum
$response = $uazapi->message()->sendAudio('5511999999999', 'https://exemplo.com/audio.mp3');

// Mensagem de voz (PTT)
$response = $uazapi->message()->sendVoice('5511999999999', 'https://exemplo.com/audio.ogg');
```

### Stickers

```php
// Figurinha
$response = $uazapi->message()->sendSticker('5511999999999', 'https://exemplo.com/sticker.webp');
```

### Contatos

```php
// Enviar um contato
$response = $uazapi->message()->sendContact('5511999999999', [
    [
        'fullName' => 'João Silva',
        'waid' => '5511888888888',
        'phoneNumber' => '+55 11 88888-8888'
    ]
]);

// Enviar múltiplos contatos
$response = $uazapi->message()->sendContact('5511999999999', [
    [
        'fullName' => 'João Silva',
        'waid' => '5511888888888',
        'phoneNumber' => '+55 11 88888-8888'
    ],
    [
        'fullName' => 'Maria Santos',
        'waid' => '5511777777777',
        'phoneNumber' => '+55 11 77777-7777'
    ]
]);
```

### Localização

```php
// Localização simples
$response = $uazapi->message()->sendLocation(
    '5511999999999',
    -23.5505199,
    -46.6333094
);

// Localização com nome e endereço
$response = $uazapi->message()->sendLocation(
    '5511999999999',
    -23.5505199,
    -46.6333094,
    'Avenida Paulista',
    'Av. Paulista, 1578 - Bela Vista, São Paulo - SP'
);
```

### Stories/Status

```php
// Status de texto com cor de fundo
$response = $uazapi->message()->sendStatusText(
    'Novidades chegando em breve!',
    backgroundColor: 7,  // Azul
    font: 2
);

// Status de imagem
$response = $uazapi->message()->sendStatusImage(
    'https://exemplo.com/foto.jpg',
    'Confira nossa novidade!'
);

// Status de vídeo com thumbnail
$response = $uazapi->message()->sendStatusVideo(
    'https://exemplo.com/video.mp4',
    'Veja o que preparamos!',
    'https://exemplo.com/thumb.jpg'
);

// Status de áudio
$response = $uazapi->message()->sendStatusAudio('https://exemplo.com/musica.mp3');

// Status de voz (PTT)
$response = $uazapi->message()->sendStatusVoice('https://exemplo.com/recado.ogg');

// Status genérico (mais flexível)
$response = $uazapi->message()->sendStatus(
    type: 'image',
    file: 'https://exemplo.com/promo.jpg',
    text: 'Promoção imperdível!',
    track_source: 'marketing'
);
```

#### Cores de Fundo para Status de Texto (1-19)

- 1-3: Tons de amarelo
- 4-6: Tons de verde
- 7-9: Tons de azul
- 10-12: Tons de lilás
- 13: Magenta
- 14-15: Tons de rosa
- 16: Marrom claro
- 17-19: Tons de cinza (19 é o padrão)

### Opções Comuns em Mensagens

Todos os métodos de envio suportam opções adicionais:

```php
// Usando parâmetros nomeados
$response = $uazapi->message()->sendText(
    '5511999999999',
    'Mensagem',
    messageDelay: 2000,
    readchat: true,
    readmessages: true,
    forward: true,
    track_source: 'crm',
    track_id: 'msg-123'
);

// Usando array de opções (para métodos que aceitam)
$options = [
    'delay' => 2000,
    'readchat' => true,
    'readmessages' => true,
    'replyid' => 'message-id',
    'mentions' => '5511999999999',
    'forward' => true,
    'track_source' => 'crm',
    'track_id' => 'msg-123'
];
```

---

## ⚡ QuickReply - Respostas Rápidas

Gerenciamento de templates de respostas rápidas.

### Métodos Disponíveis

```php
// Listar todas as respostas rápidas
$response = $uazapi->quickReply()->getAll();

// Criar resposta de texto
$response = $uazapi->quickReply()->createText('saudacao', 'Olá! Como posso ajudar?');

// Criar resposta de imagem
$response = $uazapi->quickReply()->createMedia(
    'catalogo',
    'image',
    'https://exemplo.com/catalogo.jpg'
);

// Criar resposta de documento
$response = $uazapi->quickReply()->createMedia(
    'tabela',
    'document',
    'https://exemplo.com/precos.pdf',
    'Tabela de Preços.pdf'
);

// Atualizar resposta de texto
$response = $uazapi->quickReply()->updateText(
    'rb9da9c03637452',
    'saudacao2',
    'Olá! Bem-vindo!'
);

// Atualizar resposta de mídia
$response = $uazapi->quickReply()->updateMedia(
    'rb9da9c03637452',
    'catalogo2',
    'image',
    'https://exemplo.com/novo-catalogo.jpg'
);

// Deletar resposta
$response = $uazapi->quickReply()->delete('rb9da9c03637452');

// Método genérico de edição
$response = $uazapi->quickReply()->edit(
    shortCut: 'atalho',
    type: 'text',
    id: 'rb9da9c03637452',
    text: 'Novo texto'
);
```

---

## 👥 Groups - Grupos e Comunidades

Gerenciamento completo de grupos e comunidades do WhatsApp.

### Grupos

```php
// Criar grupo
$response = $uazapi->groups()->create('Meu Grupo', [
    '5511999999999',
    '5511888888888'
]);

// Obter informações do grupo
$response = $uazapi->groups()->info('120363153742561022@g.us');

// Obter informações com link de convite
$response = $uazapi->groups()->info(
    '120363153742561022@g.us',
    getInviteLink: true
);

// Obter informações pelo código de convite
$response = $uazapi->groups()->inviteInfo('IYnl5Zg9bUcJD32rJrDzO7');

// Gerar link de convite
$response = $uazapi->groups()->inviteLink('120363153742561022@g.us');

// Entrar em grupo usando código de convite
$response = $uazapi->groups()->join('https://chat.whatsapp.com/IYnl5Zg9bUcJD32rJrDzO7');

// Sair de grupo
$response = $uazapi->groups()->leave('120363324255083289@g.us');

// Listar todos os grupos
$response = $uazapi->groups()->list();

// Listar grupos sem participantes (mais rápido)
$response = $uazapi->groups()->list(noparticipants: true);

// Resetar código de convite
$response = $uazapi->groups()->resetInviteCode('120363308883996631@g.us');
```

### Configurações de Grupo

```php
// Configurar apenas admins podem enviar mensagens
$response = $uazapi->groups()->updateAnnounce('120363339858396166@g.us', true);

// Permitir todos enviarem mensagens
$response = $uazapi->groups()->updateAnnounce('120363339858396166@g.us', false);

// Atualizar descrição do grupo
$response = $uazapi->groups()->updateDescription(
    '120363339858396166@g.us',
    'Grupo oficial de suporte técnico'
);

// Atualizar imagem do grupo
$response = $uazapi->groups()->updateImage(
    '120363308883996631@g.us',
    'https://exemplo.com/logo.jpg'
);

// Remover imagem do grupo
$response = $uazapi->groups()->updateImage('120363308883996631@g.us', 'remove');

// Configurar apenas admins podem editar informações
$response = $uazapi->groups()->updateLocked('120363308883996631@g.us', true);

// Atualizar nome do grupo
$response = $uazapi->groups()->updateName('120363339858396166@g.us', 'Novo Nome do Grupo');
```

### Gerenciar Participantes

```php
// Adicionar participantes
$response = $uazapi->groups()->updateParticipants(
    '120363308883996631@g.us',
    'add',
    ['5521987654321', '5511999887766']
);

// Remover participantes
$response = $uazapi->groups()->updateParticipants(
    '120363308883996631@g.us',
    'remove',
    ['5511999887766']
);

// Promover a administradores
$response = $uazapi->groups()->updateParticipants(
    '120363308883996631@g.us',
    'promote',
    ['5521987654321']
);

// Remover privilégios de administrador
$response = $uazapi->groups()->updateParticipants(
    '120363308883996631@g.us',
    'demote',
    ['5521987654321']
);

// Aprovar solicitações pendentes
$response = $uazapi->groups()->updateParticipants(
    '120363308883996631@g.us',
    'approve',
    ['5521987654321']
);

// Rejeitar solicitações pendentes
$response = $uazapi->groups()->updateParticipants(
    '120363308883996631@g.us',
    'reject',
    ['5521987654321']
);
```

### Comunidades

```php
// Criar comunidade
$response = $uazapi->groups()->createCommunity('Comunidade do Bairro');

// Adicionar grupos à comunidade
$response = $uazapi->groups()->editCommunityGroups(
    '120363153742561022@g.us',
    'add',
    ['120363324255083289@g.us', '120363308883996631@g.us']
);

// Remover grupos da comunidade
$response = $uazapi->groups()->editCommunityGroups(
    '120363153742561022@g.us',
    'remove',
    ['120363308883996631@g.us']
);
```

---

## 🔄 Trabalhando com Respostas

Todos os métodos retornam um array com a resposta da API ou null em caso de erro:

```php
$response = $uazapi->message()->sendText('5511999999999', 'Olá!');

// A resposta é um array ou null
if ($response !== null && !isset($response['error'])) {
    echo "Mensagem enviada! ID: " . $response['id'];
} else {
    echo "Erro: " . ($response['error'] ?? 'Erro desconhecido');
}

// Verificar campos específicos
if (isset($response['status']) && $response['status'] === 'Pending') {
    echo "Mensagem na fila de envio";
}
```

---

## 📌 Exemplos Completos

### Exemplo 1: Conectar e Enviar Mensagem

```php
use euventura\UazapiSdk\Uazapi;

$uazapi = new Uazapi('https://free.uazapi.com', 'seu-token');

// Verificar status
$status = $uazapi->instance()->status();

if ($status && isset($status['connected']) && $status['connected']) {
    // Enviar mensagem
    $uazapi->message()->sendText('5511999999999', 'WhatsApp conectado!');
}
```

### Exemplo 2: Configurar Webhook

```php
use euventura\UazapiSdk\Uazapi;

$uazapi = new Uazapi('https://free.uazapi.com', 'seu-token');

// Configurar webhook
$uazapi->webhook()->configure(
    url: 'https://meusite.com/webhook',
    events: ['messages', 'connection'],
    excludeMessages: ['wasSentByApi']
);
```

### Exemplo 3: Criar Grupo e Enviar Mensagem

```php
use euventura\UazapiSdk\Uazapi;

$uazapi = new Uazapi('https://free.uazapi.com', 'seu-token');

// Criar grupo
$grupo = $uazapi->groups()->create('Grupo de Vendas', [
    '5511999999999',
    '5511888888888'
]);

if ($grupo && isset($grupo['JID'])) {
    $groupJID = $grupo['JID'];

    // Enviar mensagem no grupo
    $uazapi->message()->sendText($groupJID, 'Bem-vindos ao grupo!');

    // Configurar grupo como apenas admins
    $uazapi->groups()->updateAnnounce($groupJID, true);
}
```

### Exemplo 4: Enviar Story/Status

```php
use euventura\UazapiSdk\Uazapi;

$uazapi = new Uazapi('https://free.uazapi.com', 'seu-token');

// Status de texto com cor azul
$uazapi->message()->sendStatusText(
    'Promoção de hoje! 50% OFF',
    backgroundColor: 7,
    font: 2
);

// Status de imagem
$uazapi->message()->sendStatusImage(
    'https://exemplo.com/promo.jpg',
    'Corre que é por tempo limitado!'
);
```

---

## 🎯 Boas Práticas

1. **Sempre configure webhooks com `excludeMessages: ['wasSentByApi']`** para evitar loops em automações

2. **Use delays apropriados** para evitar bloqueios:
   ```php
   $uazapi->message()->sendText('...', '...', messageDelay: 2000);
   ```

3. **Rastreie suas mensagens** para analytics:
   ```php
   $uazapi->message()->sendText(
       '5511999999999',
       'Mensagem',
       track_source: 'crm',
       track_id: 'ticket-12345'
   );
   ```

4. **Verifique o status antes de enviar mensagens**:
   ```php
   $status = $uazapi->instance()->status();
   if ($status && $status['connected']) {
       // Enviar mensagens
   }
   ```

5. **Trate erros adequadamente**:
   ```php
   $response = $uazapi->message()->sendText('...', '...');
   if ($response === null || isset($response['error'])) {
       // Log do erro
       error_log($response['error'] ?? 'Erro desconhecido');
   }
   ```

6. **Use os métodos específicos para melhor legibilidade**:
   ```php
   // ✅ Bom
   $uazapi->message()->sendStatusText('Olá!', backgroundColor: 7);

   // ❌ Menos legível
   $uazapi->message()->sendStatus('text', text: 'Olá!', backgroundColor: 7);
   ```

---

## 📚 Recursos Adicionais

- **Documentação da API**: https://docs.uazapi.com
- **Site oficial**: https://uazapi.com
- **Repositório GitHub**: https://github.com/euventura/uazapi-sdk
