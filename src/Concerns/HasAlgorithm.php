<?php

namespace Hanafalah\ApiHelper\Concerns;

trait HasAlgorithm
{
    protected string $__algorithm = 'RS256';
    protected mixed $__payload;
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
        $this->__payload = $payload;
        if (isset($alg)) $this->setAlgorithm($alg);
        if ($this->algorithmExists()) {
            $instance = app($this->encryption());
            // Pass the payload to the new encryption instance
            $instance->__payload = $payload;
            return $instance;
        }
        return false;
    }

    protected function setAlgorithm(string $algorithm): self
    {
        $this->__algorithm = $algorithm;
        return $this;
    }

    /**
     * Check if the algorithm exists in the array of allowed algorithms.
     *
     * @return bool
     */
    protected function algorithmExists(): bool
    {
        return \in_array($this->__algorithm, $this->__algs);
    }
}
