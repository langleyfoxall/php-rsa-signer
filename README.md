
# 🔒 PHP RSA Signer

PHP RSA Signer was designed to simplify the process of signing API
payloads with a specific private key.

## Installation

To install PHP RSA Signer, just run the following Composer command
from the root of your project.

```bash
composer require langleyfoxall/php-rsa-signer
```

## Usage

First, define the message you wish to sign. It can be a string, array,
or object. If an array or object is used, it will be JSON encoded before
being signed. 

```php
$message = 'This is my signable message';

$message = [
    'foo' => 'bar',
    'baz' => 'boff',
];

$message = \stdClass();
$message->foo = 'bar';
$message->baz = 'boff';
```

Next, instantiate the `Signer` class with the content of your private key. Its type will
be automatically determined in most cases. If not, you can specify the 
type as a second parameter.

```php
// Instantiate with private key

$signer = Signer($privateKey);

// Instantiate with private key, and specific key type

$signer = Signer($xmlPrivateKey, RSA::PRIVATE_FORMAT_XML);
```

You can then call the `getSignature` or `getBase64Signature` method to 
sign the message, and return the signature.

```php

// Get binary signature

$base64Signature = $signer->getSignature($json);

// Get base 64 Signature

$base64Signature = $signer->getBase64Signature($json);

// Get base 64 signature, using custom options for JSON encoding 

$base64Signature = $signer
            ->setJsonEncodingOptions(JSON_UNESCAPED_SLASHES)
            ->getBase64Signature($json);

```
