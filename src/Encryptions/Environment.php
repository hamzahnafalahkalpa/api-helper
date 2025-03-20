<?php

namespace Zahzah\ApiHelper\Encryptions;

use Zahzah\ApiHelper\{
    Concerns\AlgorithmRS,
    Concerns\AlgorithmHS,
    Contracts\EncryptorInterface,
    Supports\BaseApiAccess
};
use Zahzah\LaravelSupport\Concerns\Support\HasArray;

abstract class Environment extends BaseApiAccess implements EncryptorInterface{
    use AlgorithmRS;
    use AlgorithmHS;
    use HasArray;

    protected bool $__encrypt = true;

    abstract public function handle():mixed;

    /**
     * Encrypts the given string.
     *
     * @return self The current instance with the encrypted string.
     */
    protected function encrypt(): self{
        $this->__encrypt = true;
        return $this;
    }

    /**
     * Decrypts the given string.
     *
     * @return self The current instance with the decrypted string.
     */
    protected function decrypt(): self{
        $this->__encrypt = false;
        return $this;
    }
}