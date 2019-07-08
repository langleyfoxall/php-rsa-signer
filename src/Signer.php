<?php

namespace LangleyFoxall\RsaSigner;

use InvalidArgumentException;
use phpseclib\Crypt\RSA;

class Signer
{
    public $rsa;
    private $jsonEncodingOptions;

    public function __construct($key, $type = false)
    {
        $this->rsa = new RSA();
        $this->rsa->loadKey($key, $type);
        $this->rsa->setSignatureMode(RSA::SIGNATURE_PKCS1);
        $this->rsa->setHash('sha256');
    }

    public function getSignature($message) :string
    {
        $message = $this->encodeMessage($message);
        $this->sanityCheckMessage($message);

        return $this->rsa->sign($message);
    }

    public function getBase64Signature($message) :string
    {
        return base64_encode($this->getSignature($message));
    }

    public function setJsonEncodingOptions(int $jsonEncodingOptions)
    {
        $this->jsonEncodingOptions = $jsonEncodingOptions;
        return $this;
    }

    private function encodeMessage($message) :string
    {
        if (is_object($message) || is_array($message)) {
            $message = json_encode($message, $this->jsonEncodingOptions);
        }

        return $message;
    }

    private function sanityCheckMessage($message)
    {
        if (!is_string($message)) {
            throw new InvalidArgumentException('Message must be a string, or an array/object that can be JSON encoded.');
        }

        if (empty($message)) {
            throw new InvalidArgumentException('Message must not be empty.');
        }
    }

}