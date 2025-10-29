# Uazapi SDK para PHP

SDK completo em PHP para integração com a API UazAPI do WhatsApp. Feita Com Saloon.

[![codecov](https://codecov.io/gh/euventura/uazapi-sdk/graph/badge.svg?token=PLOEADLL22)](https://codecov.io/gh/euventura/uazapi-sdk)
[![Tests](https://github.com/euventura/uazapi-sdk/actions/workflows/tests.yml/badge.svg)](https://github.com/euventura/uazapi-sdk/actions/workflows/tests.yml)
[![License](https://img.shields.io/badge/License-Apache_2.0-blue.svg)](https://opensource.org/licenses/Apache-2.0)

## 📞 Uazapi

- Documentação da API: https://docs.uazapi.com
- Site oficial: https://uazapi.com

## 📋 Requisitos

- PHP 8.1 ou superior
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