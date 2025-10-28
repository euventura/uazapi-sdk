# UazAPI SDK - Requests Criados

Este documento lista todos os requests criados para o UazAPI SDK, baseados na especificação OpenAPI.

## Estrutura de Pastas

```
src/Requests/
├── Instance/          # Requests de gerenciamento de instância
├── Profile/           # Requests de perfil do WhatsApp
├── Webhook/           # Requests de configuração de webhooks
├── Message/           # Requests de envio de mensagens
└── QuickReply/        # Requests de respostas rápidas
```

## Requests Criados

### 📱 Instância (Instance/)

1. **ConnectRequest** - Conectar instância ao WhatsApp
    - Endpoint: `POST /instance/connect`
    - Parâmetros: `phone` (opcional)

2. **DisconnectRequest** - Desconectar instância
    - Endpoint: `POST /instance/disconnect`

3. **StatusRequest** - Verificar status da instância
    - Endpoint: `GET /instance/status`

4. **DeleteInstanceRequest** - Deletar instância
    - Endpoint: `DELETE /instance`

5. **UpdateInstanceNameRequest** - Atualizar nome da instância
    - Endpoint: `POST /instance/updateInstanceName`
    - Parâmetros: `name`

6. **GetPrivacyRequest** - Buscar configurações de privacidade
    - Endpoint: `GET /instance/privacy`

7. **UpdatePrivacyRequest** - Alterar configurações de privacidade
    - Endpoint: `POST /instance/privacy`
    - Parâmetros: `privacySettings` (array)

### 👤 Perfil (Profile/)

1. **UpdateProfileNameRequest** - Alterar nome do perfil do WhatsApp
    - Endpoint: `POST /profile/name`
    - Parâmetros: `name`

2. **UpdateProfileImageRequest** - Alterar imagem do perfil do WhatsApp
    - Endpoint: `POST /profile/image`
    - Parâmetros: `image` (URL, base64 ou "remove")

### 🔗 Webhooks (Webhook/)

1. **GetWebhookRequest** - Ver configuração do webhook
    - Endpoint: `GET /webhook`

2. **ConfigureWebhookRequest** - Configurar webhook da instância
    - Endpoint: `POST /webhook`
    - Parâmetros:
        - `url` (obrigatório)
        - `events` (array, obrigatório)
        - `enabled` (opcional, padrão: true)
        - `excludeMessages` (array, opcional)
        - `addUrlEvents` (opcional, padrão: false)
        - `addUrlTypesMessages` (opcional, padrão: false)
        - `action` (opcional: "add", "update", "delete")
        - `id` (opcional, para update/delete)

### 💬 Mensagens (Message/)

1. **SendTextRequest** - Enviar mensagem de texto
    - Endpoint: `POST /send/text`
    - Parâmetros obrigatórios: `number`, `text`
    - Parâmetros opcionais:
        - Link Preview: `linkPreview`, `linkPreviewTitle`, `linkPreviewDescription`, `linkPreviewImage`,
          `linkPreviewLarge`
        - Comuns: `replyid`, `mentions`, `readchat`, `readmessages`, `delay`, `forward`, `track_source`, `track_id`

2. **SendMediaRequest** - Enviar mídia (imagem, vídeo, áudio, documento)
    - Endpoint: `POST /send/media`
    - Parâmetros obrigatórios: `number`, `type`, `file`
    - Tipos suportados: `image`, `video`, `document`, `audio`, `myaudio`, `ptt`, `sticker`
    - Parâmetros opcionais: `text`, `docName`, + campos comuns

3. **SendContactRequest** - Enviar cartão de contato (vCard)
    - Endpoint: `POST /send/contact`
    - Parâmetros obrigatórios: `number`, `contacts` (array)
    - Parâmetros opcionais: campos comuns

4. **SendLocationRequest** - Enviar localização
    - Endpoint: `POST /send/location`
    - Parâmetros obrigatórios: `number`, `latitude`, `longitude`
    - Parâmetros opcionais: `name`, `address`, + campos comuns

### ⚡ Respostas Rápidas (QuickReply/)

1. **EditQuickReplyRequest** - Criar, atualizar ou excluir resposta rápida
    - Endpoint: `POST /quickreply/edit`
    - Parâmetros obrigatórios: `shortCut`, `type`
    - Parâmetros opcionais:
        - `id` (para update/delete)
        - `delete` (boolean)
        - `text` (para tipo text)
        - `file` (para tipos de mídia)
        - `docName` (para tipo document)

2. **GetAllQuickRepliesRequest** - Listar todas as respostas rápidas
    - Endpoint: `GET /quickreply/showall`

## Campos Comuns em Mensagens

Todos os requests de envio de mensagens suportam os seguintes campos opcionais:

- **`delay`** (int): Atraso em milissegundos antes do envio
- **`readchat`** (bool): Marcar conversa como lida após envio
- **`readmessages`** (bool): Marcar últimas mensagens recebidas como lidas
- **`replyid`** (string): ID da mensagem para responder
- **`mentions`** (string): Números para mencionar (separados por vírgula)
- **`forward`** (bool): Marca a mensagem como encaminhada
- **`track_source`** (string): Origem do rastreamento
- **`track_id`** (string): ID para rastreamento (aceita duplicados)

## Exemplo de Uso

```php
use UazApi\Requests\Message\SendTextRequest;
use UazApi\UazapiApiConnector;

$connector = new UazapiApiConnector('seu-token-aqui');

// Enviar mensagem de texto simples
$request = new SendTextRequest(
    number: '5511999999999',
    text: 'Olá! Como posso ajudar?'
);

$response = $connector->send($request);

// Enviar mensagem com link preview
$request = new SendTextRequest(
    number: '5511999999999',
    text: 'Confira: https://exemplo.com',
    linkPreview: true,
    delay: 2000
);

$response = $connector->send($request);
```

## Observações

- Todos os requests usam o padrão Saloon para requisições HTTP
- As propriedades são definidas usando PHP 8.0+ promoted properties
- Validação de tipos é feita automaticamente pelo PHP
- Campos opcionais são incluídos no body apenas se não forem null

