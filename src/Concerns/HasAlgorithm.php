<?php

namespace Hanafalah\ApiHelper\Concerns;

trait HasAlgorithm
{
    protected static string $__algorithm = 'RS256';
    protected static mixed $__payload;
    protected static mixed $__decode_result;
    protected array $__algs = [
        'HS256',
        'HS384',
        'HS512',
        'RS256',
        'RS384',
        'RS512',
        'ES256',
        'ES384',
        'ES512'
    ];

    protected function chooseAlgorithm(mixed $payload, ?string $alg = null): mixed
    {
        self::$__payload = $payload;
        if (isset($alg)) $this->setAlgorithm($alg);
        if ($this->algorithmExists()) return app($this->encryption());
        return false;
    }

    protected function setDecoded(mixed $result): self
    {
        static::$__decode_result = $result;
        return $this;
    }

    protected function getDecoded(): mixed
    {
        return static::$__decode_result ?? null;
    }

    protected function setAlgorithm(string $algorithm): self
    {
        static::$__algorithm = $algorithm;
        return $this;
    }

    /**
     * Check if the algorithm exists in the array of allowed algorithms.
     *
     * @return bool
     */
    protected function algorithmExists(): bool
    {
        return \in_array(self::$__algorithm, $this->__algs);
    }
}
