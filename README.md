
# PHP RSA Signer

PHP RSA Signer was designed to simplify the process of signing API
payloads with a specific private key.

## Installation

To install PHP RSA Signer, just run the following Composer command
from the root of your project.

```php
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

Next, instantiate the `Signer` class with the private key. Its type will
be automatically determined in most cases. If not, you can specify the 
type as a second parameter.

```php
$base64Signature = (new Signer($privateKey))
            ->getSignature($json);

$base64Signature = (new Signer($privateKey))
            ->setJsonEncodingOptions(JSON_UNESCAPED_SLASHES)
            ->getBase64Signature($json);

$base64Signature = (new Signer($privateKey))
            ->setJsonEncodingOptions(JSON_UNESCAPED_SLASHES)
            ->getBase64Signature($json);

```