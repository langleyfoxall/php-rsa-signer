<?php

namespace LangleyFoxall\RsaSigner;

use InvalidArgumentException;
use phpseclib\Crypt\RSA;

/**
 * Class Signer
 * @package LangleyFoxall\RsaSigner
 */
class Signer
{
    public $rsa;
    private $jsonEncodingOptions;

    /**
     * Signer constructor.
     *
     * Accepts a private key used for signing messages, and an optional type.
     * If no type is specified, the type will be determined automatically where
     * possible.
     *
     * @see \phpseclib\Crypt\RSA::PRIVATE_FORMAT_*
     *
     * @param $key
     * @param bool $type
     */
    public function __construct($key, $type = false)
    {
        $this->rsa = new RSA();
        $this->rsa->loadKey($key, $type);
        $this->rsa->setSignatureMode(RSA::SIGNATURE_PKCS1);
        $this->rsa->setHash('sha256');
    }

    /**
     * Signs a specified message and returns the binary signature.
     *
     * The message can be a string, array or object. If an array or object is
     * passed, it is first JSON encoded.
     *
     * @param $message
     * @return string
     */
    public function getSignature($message) :string
    {
        $message = $this->encodeMessage($message);
        $this->sanityCheckMessage($message);

        return $this->rsa->sign($message);
    }

    /**
     * Signs a specified message and returns the base 64 encoded signature.
     *
     * The message can be a string, array or object. If an array or object is
     * passed, it is first JSON encoded.
     *
     * @param $message
     * @return string
     */
    public function getBase64Signature($message) :string
    {
        return base64_encode($this->getSignature($message));
    }

    /**
     * Sets the JSON_* options used for JSON encoding the message if it is
     * passed as an array or object.
     *
     * @param int $jsonEncodingOptions
     * @return $this
     */
    public function setJsonEncodingOptions(int $jsonEncodingOptions)
    {
        $this->jsonEncodingOptions = $jsonEncodingOptions;
        return $this;
    }

    /**
     * Peforms the JSON encoding of the message if it is an array or object,
     * using the JSON encoding options previously set.
     *
     * @param $message
     * @return string
     */
    private function encodeMessage($message) :string
    {
        if (is_object($message) || is_array($message)) {
            $message = json_encode($message, $this->jsonEncodingOptions);
        }

        return $message;
    }

    /**
     * Verifies that the message to sign is a valid string and not empty.
     *
     * @param $message
     */
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