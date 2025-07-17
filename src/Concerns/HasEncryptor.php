<?php

namespace Hanafalah\ApiHelper\Concerns;

trait HasEncryptor
{
    use HasAlgorithm;

    protected bool $encrypt = true;

    protected function decrypting(mixed $data): mixed
    {
        $this->setDecoded($this->chooseAlgorithm($data)->decrypt()->handle());
        return self::$__decode_result;
    }

    public function encrypting(mixed $data): bool|string
    {
        return $this->chooseAlgorithm($data)->encrypt()->handle();
    }
}
