<?php

namespace Hanafalah\ApiHelper\Concerns;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

trait AlgorithmRS
{
    protected string $__private_key, $__public_key;

    /**
     * Encrypts the payload using the RS256 algorithm, and returns the
     * encrypted token.
     *
     * @return string The encrypted token.
     */
    protected function algorithmRS()
    {
        return ($this->__encrypt) ? JWT::encode(self::$__payload, $this->__private_key, static::$__algorithm)
            : JWT::decode(self::$__payload, new Key($this->__public_key, static::$__algorithm), $this->__rsJwtHeaders);
    }

    /**
     * Sets the public key to be used for decrypting the JWT token.
     *
     * If no key is provided, the public key of the current ApiAccess model
     * will be used.
     *
     * @param string $key The public key to be used.
     * @return self The current instance after setting the public key.
     */
    protected function setPublicKey(?string $key = null): self
    {
        $this->__public_key = $key ?? $this->getApiAccess()->public_key;
        return $this;
    }

    /**
     * Sets the private key to be used for encrypting the JWT token.
     *
     * If no key is provided, the private key of the current ApiAccess model
     * will be used.
     *
     * @param string $key The private key to be used.
     * @return self The current instance after setting the private key.
     */
    protected function setPrivateKey(?string $key = null): self
    {
        $this->__private_key = $key ?? $this->getApiAccess()->private_key;
        return $this;
    }

    protected function setRsKeys(): self
    {
        $this->setPublicKey()->setPrivateKey();
        return $this;
    }

    /**
     * Gets the public key.
     *
     * @return string The public key.
     */
    protected function getPublicKey(): string
    {
        return $this->__public_key;
    }

    /**
     * Gets the private key.
     *
     * @return string The private key.
     */
    protected function getPrivateKey(): string
    {
        return $this->__private_key;
    }

    /**
     * Encrypts the payload using the RS256 algorithm, and returns the
     * encrypted token.
     *
     * @return string The encrypted token.
     */
    protected function processRS()
    {
        $key = ($this->__encrypt) ? $this->__private_key : $this->__public_key;
        return $this->process($key);
    }
}
