<?php

namespace Hanafalah\ApiHelper\Encryptions;

use Hanafalah\ApiHelper\{
    Concerns\AlgorithmRS,
    Concerns\AlgorithmHS,
    Contracts\Encryptions\EncryptorInterface,
    Supports\BaseApiAccess
};
use Hanafalah\ApiHelper\Concerns\AlgorithmES;
use Hanafalah\LaravelSupport\Concerns\Support\HasArray;

// abstract class Environment extends BaseApiAccess implements EncryptorInterface
abstract class Environment implements EncryptorInterface
{
    use AlgorithmRS;
    use AlgorithmHS;
    use AlgorithmES;
    use HasArray;

    protected bool $__encrypt = true;

    abstract public function handle(): mixed;

    /**
     * Encrypts the given string.
     *
     * @return self The current instance with the encrypted string.
     */
    public function encrypt(): self
    {
        $this->__encrypt = true;
        return $this;
    }

    /**
     * Decrypts the given string.
     *
     * @return self The current instance with the decrypted string.
     */
    public function decrypt(): self
    {
        $this->__encrypt = false;
        return $this;
    }
}
