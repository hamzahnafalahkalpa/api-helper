<?php

namespace Hanafalah\ApiHelper\Validators;

use Hanafalah\ApiHelper\{
    Contracts\Validators\TokenValidator,
    Supports\BaseApiAccess,
    Exceptions
};

abstract class Environment extends BaseApiAccess implements TokenValidator
{
    abstract public function handle(): bool;
    abstract public function tokenValidator(): self;

    /**
     * Validate the timestamp difference
     *
     * Validate if the timestamp has expired given the THRESHOLD
     *
     * @return self
     *
     * @throws Exceptions\TokenExpiredException
     * @throws Exceptions\InvalidTimestamp
     */
    protected function timeValidator(): self
    {
        if ((time() - $this->getTimestamp()) > $this->__threshold) {
            ($this->getToken() !== null)
                ? throw new Exceptions\TokenExpiredException
                : throw new Exceptions\InvalidTimestamp;
        }
        return $this;
    }
}
