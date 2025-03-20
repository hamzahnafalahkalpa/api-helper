<?php

namespace Zahzah\ApiHelper\Concerns;

use Zahzah\ApiHelper\Exceptions;

trait HasToken{
    protected static string $__token;
    protected string $__token_access_name = 'access-token';    

    /**
     * Gets the token of the current instance.
     *
     * @return string|null
     */
    public function getToken() : ?string{
        return static::$__token ?? null;
    }

    /**
     * Sets the token of the current instance.
     *
     * @param string|null $token The token to set.
     * @return self
     */
    protected function setToken(?string $token=null): self{
        static::$__token = $token ?? $this->getApiAccess()->token;
        self::$__generated_token['token'] = static::$__token;
        self::$__generated_token['token'] = static::$__token;
        return $this;
    }    
}