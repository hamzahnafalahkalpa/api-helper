<?php

namespace Hanafalah\ApiHelper\Concerns;

trait AlgorithmHS
{
    protected string $__secret_key;

    /**
     * Sets the public key to be used for decrypting the JWT token.
     *
     * If no key is provided, the public key of the current ApiAccess model
     * will be used.
     *
     * @param string $key The public key to be used.
     * @return self The current instance after setting the public key.
     */
    protected function setSecretKey(?string $key = null): self
    {
        $this->__secret_key = $key ?? $this->getApiAccess()->secret ?? '';
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
    protected function getSecretKey(): string
    {
        return $this->__secret_key;
    }

    /**
     * Encrypts the payload using the RS256 algorithm, and returns the
     * encrypted token.
     *
     * @return string The encrypted token.
     */
    protected function processHS()
    {
        return $this->process($this->__secret_key);
    }
}
