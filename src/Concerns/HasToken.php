<?php

namespace Hanafalah\ApiHelper\Concerns;

use Hanafalah\ApiHelper\Exceptions;

trait HasToken
{
    protected string $__token;
    protected string $__token_access_name = 'access-token';

    public function getTokenAccessName(): string{
        return $this->__token_access_name;
    }

    /**
     * Gets the token of the current instance.
     *
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->__token ?? null;
    }

    /**
     * Sets the token of the current instance.
     *
     * @param string|null $token The token to set.
     * @return self
     */
    protected function setToken(?string $token = null): self
    {
        $this->__token = $token ?? $this->getApiAccess()->token;
        $this->__generated_token['token'] = $this->__token;
        $this->__generated_token['token'] = $this->__token;
        return $this;
    }
}
