# Uazapi SDK para PHP

SDK completo em PHP para integração com a API UazAPI do WhatsApp. Feita Com Saloon.

## 📞 Uazapi

- Documentação da API: https://docs.uazapi.com
- Site oficial: https://uazapi.com

## 📋 Requisitos

- PHP 8.0 ou superior
- Composer

## 📦 Instalação

```bash
composer require euventura/uazapi-sdk
```
## 💡 Exemplos de Uso


```php
$uazapi = new \euventura\UazapiSdk\Uazapi($server, $token);
// Conectar Instancia
$response = $uazapi->instance()->connect($phone)


```

### Enviar Mensagens

```php
$uazapi = new \euventura\UazapiSdk\Uazapi($server, $token);
// Texto simples
$response = $uazapi->message()->sendText($number, $text);
// Imagem com legenda
$uazapi->message()->('5522999999999', 'https://exemplo.com/foto.jpg', 'Veja isto!');
// Documento
$uazapi->message()->sendDocument('5512999999999', 'https://exemplo.com/doc.pdf', 'Documento.pdf');
// Localização
$uazapi->message()->sendLocation('5511999999999', -23.5505199, -46.6333094, 'Av. Paulista');
```

## 📝 Licença

MIT