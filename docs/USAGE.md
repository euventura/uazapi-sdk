# UazAPI SDK - Resource Classes

Este documento explica como usar as classes Resource do UazAPI SDK.

## Estrutura

Cada categoria de endpoints possui sua própria classe Resource:

- **UazapiInstance** - Gerenciamento de instância
- **UazapiProfile** - Perfil do WhatsApp
- **UazapiWebhook** - Configuração de webhooks
- **UazapiMessage** - Envio de mensagens
- **UazapiQuickReply** - Respostas rápidas

## Uso Básico

```php
use UazApi\UazapiApiConnector;

// Inicializar o connector com seu token
$connector = new UazapiApiConnector('seu-token-aqui');

// Usar as classes Resource
$instance = new UazapiInstance($connector);
$profile = new UazapiProfile($connector);
$webhook = new UazapiWebhook($connector);
$message = new UazapiMessage($connector);
$quickReply = new UazapiQuickReply($connector);
```

---

## 📱 UazapiInstance

Gerenciamento do ciclo de vida da instância.

### Métodos Disponíveis

```php
// Conectar ao WhatsApp (sem telefone = QR Code, com telefone = código de pareamento)
$response = $instance->connect();
$response = $instance->connect('5511999999999');

// Desconectar
$response = $instance->disconnect();

// Verificar status
$response = $instance->status();

// Deletar instância
$response = $instance->delete();

// Atualizar nome da instância
$response = $instance->updateName('Minha Nova Instância');

// Buscar configurações de privacidade
$response = $instance->getPrivacy();

// Atualizar configurações de privacidade
$response = $instance->updatePrivacy([
    'groupadd' => 'contacts',
    'last' => 'none',
    'status' => 'contacts',
    'profile' => 'contacts'
]);
```

---

## 👤 UazapiProfile

Gerenciamento do perfil do WhatsApp.

### Métodos Disponíveis

```php
// Alterar nome do perfil
$response = $profile->updateName('Minha Loja');

// Alterar foto do perfil (URL)
$response = $profile->updateImage('https://exemplo.com/foto.jpg');

// Alterar foto do perfil (base64)
$response = $profile->updateImage('data:image/jpeg;base64,/9j/4AAQ...');

// Remover foto do perfil
$response = $profile->removeImage();
```

---

## 🔗 UazapiWebhook

Configuração de webhooks para receber eventos.

### Métodos Disponíveis

```php
// Ver configuração atual
$response = $webhook->get();

// Configurar webhook (modo simples - recomendado)
$response = $webhook->configure(
    url: 'https://meusite.com/webhook',
    events: ['messages', 'connection'],
    enabled: true,
    excludeMessages: ['wasSentByApi'] // Importante para evitar loops
);

// Configurar com opções avançadas
$response = $webhook->configure(
    url: 'https://meusite.com/webhook',
    events: ['messages', 'messages_update', 'connection'],
    enabled: true,
    excludeMessages: ['wasSentByApi', 'isGroupYes'],
    addUrlEvents: true,
    addUrlTypesMessages: true
);

// === Modo Avançado (múltiplos webhooks) ===

// Adicionar novo webhook
$response = $webhook->add(
    url: 'https://outro-site.com/webhook',
    events: ['presence', 'groups']
);

// Atualizar webhook existente
$response = $webhook->update(
    id: 'webhook-id-123',
    url: 'https://site-atualizado.com/webhook',
    events: ['messages']
);

// Deletar webhook
$response = $webhook->delete('webhook-id-123');
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

## 💬 UazapiMessage

Envio de mensagens de diferentes tipos.

### Métodos Disponíveis

#### Mensagens de Texto

```php
// Texto simples
$response = $message->sendText('5511999999999', 'Olá! Como posso ajudar?');

// Texto com opções
$response = $message->sendText('5511999999999', 'Confira nosso site!', [
    'linkPreview' => true,
    'delay' => 2000,
    'readchat' => true
]);

// Texto com preview personalizado
$response = $message->sendText('5511999999999', 'https://exemplo.com', [
    'linkPreview' => true,
    'linkPreviewTitle' => 'Título Personalizado',
    'linkPreviewDescription' => 'Descrição do link',
    'linkPreviewImage' => 'https://exemplo.com/thumb.jpg',
    'linkPreviewLarge' => true
]);

// Respondendo mensagem
$response = $message->sendText('5511999999999', 'Respondendo!', [
    'replyid' => '3EB0538DA65A59F6D8A251'
]);

// Mencionando usuários em grupo
$response = $message->sendText('120363012345678901@g.us', 'Olá @todos!', [
    'mentions' => 'all'
]);
```

#### Imagens

```php
// Imagem simples
$response = $message->sendImage('5511999999999', 'https://exemplo.com/foto.jpg');

// Imagem com legenda
$response = $message->sendImage('5511999999999', 'https://exemplo.com/foto.jpg', 'Veja esta foto!');

// Imagem com opções
$response = $message->sendImage('5511999999999', 'https://exemplo.com/foto.jpg', 'Foto', [
    'delay' => 3000,
    'forward' => true
]);
```

#### Vídeos

```php
// Vídeo simples
$response = $message->sendVideo('5511999999999', 'https://exemplo.com/video.mp4');

// Vídeo com legenda
$response = $message->sendVideo('5511999999999', 'https://exemplo.com/video.mp4', 'Confira!');
```

#### Documentos

```php
// Documento com nome personalizado
$response = $message->sendDocument(
    '5511999999999',
    'https://exemplo.com/contrato.pdf',
    'Contrato.pdf',
    'Segue o documento'
);
```

#### Áudios

```php
// Áudio comum
$response = $message->sendAudio('5511999999999', 'https://exemplo.com/audio.mp3');

// Mensagem de voz (PTT)
$response = $message->sendVoice('5511999999999', 'https://exemplo.com/audio.ogg');
```

#### Stickers

```php
// Figurinha
$response = $message->sendSticker('5511999999999', 'https://exemplo.com/sticker.webp');
```

#### Mídia Genérica

```php
// Enviar qualquer tipo de mídia
$response = $message->sendMedia(
    number: '5511999999999',
    type: 'image',
    file: 'https://exemplo.com/foto.jpg',
    caption: 'Legenda opcional',
    options: ['delay' => 2000]
);
```

#### Contatos

```php
// Enviar cartão de contato
$response = $message->sendContact('5511999999999', [
    [
        'fullName' => 'João Silva',
        'waid' => '5511888888888',
        'phoneNumber' => '+55 11 88888-8888'
    ]
]);
```

#### Localização

```php
// Localização simples
$response = $message->sendLocation(
    '5511999999999',
    -23.5505199,
    -46.6333094
);

// Localização com nome e endereço
$response = $message->sendLocation(
    '5511999999999',
    -23.5505199,
    -46.6333094,
    'Avenida Paulista',
    'Av. Paulista, 1578 - Bela Vista, São Paulo - SP'
);
```

### Opções Comuns em Mensagens

Todos os métodos de envio suportam as seguintes opções:

```php
$options = [
    'delay' => 2000,                    // Atraso em ms
    'readchat' => true,                 // Marcar conversa como lida
    'readmessages' => true,             // Marcar mensagens como lidas
    'replyid' => 'message-id',          // ID da mensagem para responder
    'mentions' => '5511999999999,all',  // Mencionar usuários
    'forward' => true,                  // Marcar como encaminhada
    'track_source' => 'crm',            // Origem do rastreamento
    'track_id' => 'msg-123'             // ID de rastreamento
];
```

---

## ⚡ UazapiQuickReply

Gerenciamento de respostas rápidas.

### Métodos Disponíveis

```php
// Listar todas
$response = $quickReply->getAll();

// Criar resposta de texto
$response = $quickReply->createText('saudacao', 'Olá! Como posso ajudar?');

// Criar resposta de imagem
$response = $quickReply->createMedia(
    'catalogo',
    'image',
    'https://exemplo.com/catalogo.jpg'
);

// Criar resposta de documento
$response = $quickReply->createMedia(
    'tabela',
    'document',
    'https://exemplo.com/precos.pdf',
    'Tabela de Preços.pdf'
);

// Atualizar resposta de texto
$response = $quickReply->updateText(
    'rb9da9c03637452',
    'saudacao2',
    'Olá! Bem-vindo!'
);

// Atualizar resposta de mídia
$response = $quickReply->updateMedia(
    'rb9da9c03637452',
    'catalogo2',
    'image',
    'https://exemplo.com/novo-catalogo.jpg'
);

// Deletar resposta
$response = $quickReply->delete('rb9da9c03637452');

// Método genérico de edição
$response = $quickReply->edit(
    shortCut: 'atalho',
    type: 'text',
    id: 'rb9da9c03637452',
    text: 'Novo texto'
);
```

---

## 🔄 Trabalhando com Respostas

Todos os métodos retornam um objeto `Saloon\Http\Response`:

```php
$response = $message->sendText('5511999999999', 'Olá!');

// Verificar sucesso
if ($response->successful()) {
    $data = $response->json();
    echo "Mensagem enviada! ID: " . $data['id'];
}

// Tratar erros
if ($response->failed()) {
    $error = $response->json();
    echo "Erro: " . $error['error'];
}

// Status HTTP
$statusCode = $response->status();

// Corpo da resposta
$body = $response->body();
$json = $response->json();
$array = $response->array();
```

---

## 📌 Exemplos Completos

### Exemplo 1: Conectar e Enviar Mensagem

```php
use UazApi\UazapiApiConnector;
use UazApi\UazapiInstance;
use UazApi\UazapiMessage;

$connector = new UazapiApiConnector('seu-token');
$instance = new UazapiInstance($connector);
$message = new UazapiMessage($connector);

// Verificar status
$status = $instance->status();
if ($status->json()['status']['connected']) {
    // Enviar mensagem
    $message->sendText('5511999999999', 'WhatsApp conectado!');
}
```

### Exemplo 2: Configurar Webhook

```php
use UazApi\UazapiApiConnector;
use UazApi\UazapiWebhook;

$connector = new UazapiApiConnector('seu-token');
$webhook = new UazapiWebhook($connector);

// Configurar webhook
$webhook->configure(
    url: 'https://meusite.com/webhook',
    events: ['messages', 'connection'],
    excludeMessages: ['wasSentByApi']
);
```

### Exemplo 3: Enviar Mensagem com Rastreamento

```php
use UazApi\UazapiApiConnector;
use UazApi\UazapiMessage;

$connector = new UazapiApiConnector('seu-token');
$message = new UazapiMessage($connector);

// Enviar com rastreamento
$response = $message->sendText('5511999999999', 'Mensagem rastreada', [
    'track_source' => 'crm',
    'track_id' => 'ticket-12345',
    'delay' => 2000
]);

if ($response->successful()) {
    echo "Mensagem enviada com sucesso!";
}
```

---

## 🎯 Boas Práticas

1. **Sempre configure webhooks com `excludeMessages: ['wasSentByApi']`** para evitar loops em automações

2. **Use delays apropriados** para evitar bloqueios:
   ```php
   $message->sendText('...', '...', ['delay' => 2000]);
   ```

3. **Rastreie suas mensagens** para analytics:
   ```php
   $message->sendText('...', '...', [
       'track_source' => 'nome-do-sistema',
       'track_id' => 'identificador-unico'
   ]);
   ```

4. **Verifique o status antes de enviar mensagens**:
   ```php
   $status = $instance->status();
   if ($status->json()['status']['connected']) {
       // Enviar mensagens
   }
   ```

5. **Trate erros adequadamente**:
   ```php
   $response = $message->sendText('...', '...');
   if ($response->failed()) {
       // Log do erro
       error_log($response->json()['error']);
   }
   ```

