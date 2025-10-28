# UazAPI SDK para PHP

SDK completo em PHP para integração com a API UazAPI do WhatsApp, utilizando o
framework [Saloon](https://docs.saloon.dev/).

## 📋 Requisitos

- PHP 8.0 ou superior
- Composer

## 📦 Instalação

```bash
composer require uazapi/sdk
```

## 🚀 Início Rápido

```php
<?php

require 'vendor/autoload.php';

use UazApi\UazapiApiConnector;
use UazApi\UazapiInstance;
use UazApi\UazapiMessage;

// Inicializar o connector com seu token
$connector = new UazapiApiConnector('seu-token-aqui');

// Usar as classes Resource
$instance = new UazapiInstance($connector);
$message = new UazapiMessage($connector);

// Verificar status da instância
$status = $instance->status();
echo "Status: " . $status->json()['instance']['status'];

// Enviar mensagem
$response = $message->sendText('5511999999999', 'Olá do UazAPI SDK!');
if ($response->successful()) {
    echo "Mensagem enviada com sucesso!";
}
```

## 📚 Documentação

- **[USAGE.md](docs/USAGE.md)** - Guia completo de uso das classes Resource
- **[REQUESTS.md](docs/REQUESTS.md)** - Referência de todos os requests disponíveis

## 🏗️ Estrutura

O SDK está organizado em classes Resource que correspondem às categorias da API:

### Classes Resource

| Classe             | Descrição                  | Documentação                                |
|--------------------|----------------------------|---------------------------------------------|
| `UazapiInstance`   | Gerenciamento de instância | [Ver docs](docs/USAGE.md#-uazapiinstance)   |
| `UazapiProfile`    | Perfil do WhatsApp         | [Ver docs](docs/USAGE.md#-uazapiprofile)    |
| `UazapiWebhook`    | Webhooks e eventos         | [Ver docs](docs/USAGE.md#-uazapiwebhook)    |
| `UazapiMessage`    | Envio de mensagens         | [Ver docs](docs/USAGE.md#-uazapimessage)    |
| `UazapiQuickReply` | Respostas rápidas          | [Ver docs](docs/USAGE.md#-uazapiquickreply) |

### Estrutura de Diretórios

```
src/
├── Requests/
│   ├── Instance/      # Requests de instância
│   ├── Profile/       # Requests de perfil
│   ├── Webhook/       # Requests de webhook
│   ├── Message/       # Requests de mensagens
│   └── QuickReply/    # Requests de respostas rápidas
├── UazapiApiConnector.php
├── UazapiResource.php
├── UazapiInstance.php
├── UazapiProfile.php
├── UazapiWebhook.php
├── UazapiMessage.php
└── UazapiQuickReply.php
```

## 💡 Exemplos de Uso

### Conectar ao WhatsApp

```php
$instance = new UazapiInstance($connector);

// Gerar QR Code
$response = $instance->connect();

// Ou usar código de pareamento
$response = $instance->connect('5511999999999');
```

### Configurar Webhook

```php
$webhook = new UazapiWebhook($connector);

$webhook->configure(
    url: 'https://meusite.com/webhook',
    events: ['messages', 'connection'],
    excludeMessages: ['wasSentByApi'] // Importante!
);
```

### Enviar Mensagens

```php
$message = new UazapiMessage($connector);

// Texto simples
$message->sendText('5511999999999', 'Olá!');

// Imagem com legenda
$message->sendImage('5511999999999', 'https://exemplo.com/foto.jpg', 'Veja isto!');

// Documento
$message->sendDocument('5511999999999', 'https://exemplo.com/doc.pdf', 'Documento.pdf');

// Localização
$message->sendLocation('5511999999999', -23.5505199, -46.6333094, 'Av. Paulista');
```

### Gerenciar Respostas Rápidas

```php
$quickReply = new UazapiQuickReply($connector);

// Criar resposta de texto
$quickReply->createText('saudacao', 'Olá! Como posso ajudar?');

// Listar todas
$response = $quickReply->getAll();
```

### Atualizar Perfil

```php
$profile = new UazapiProfile($connector);

// Alterar nome
$profile->updateName('Minha Loja');

// Alterar foto
$profile->updateImage('https://exemplo.com/logo.jpg');
```

## 🎯 Recursos

### ✅ Implementado

- ✅ Gerenciamento de instância (conectar, desconectar, status, etc)
- ✅ Configuração de perfil (nome, imagem)
- ✅ Configuração de webhooks (simples e avançado)
- ✅ Envio de mensagens (texto, imagem, vídeo, áudio, documento, etc)
- ✅ Respostas rápidas (criar, editar, deletar, listar)
- ✅ Suporte a campos opcionais (delay, mentions, replyid, etc)
- ✅ Rastreamento de mensagens (track_source, track_id)
- ✅ Link preview personalizado
- ✅ Configurações de privacidade

### 🚧 Roadmap

- ⏳ Gerenciamento de grupos e comunidades
- ⏳ Gerenciamento de contatos
- ⏳ Gerenciamento de chats e leads (CRM)
- ⏳ Chatbot e IA
- ⏳ Mensagens em massa
- ⏳ Integração com Chatwoot

## 🔒 Segurança

- Nunca compartilhe seu token de API
- Use variáveis de ambiente para armazenar credenciais
- Configure webhooks com `excludeMessages: ['wasSentByApi']` para evitar loops

## 📝 Licença

MIT

## 🤝 Contribuindo

Contribuições são bem-vindas! Por favor, abra uma issue ou pull request.

## 📞 Suporte

- Documentação da API: https://docs.uazapi.com
- Issues: https://github.com/seu-usuario/uazapi-sdk/issues

## 🙏 Créditos

Desenvolvido com [Saloon](https://docs.saloon.dev/) - Um framework elegante para construir SDKs em PHP.

