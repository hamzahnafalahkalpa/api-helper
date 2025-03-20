<?php

namespace Hanafalah\ApiHelper\Concerns;

trait InitUser
{
    public static $__api_user;

    /**
     * Get the authenticated user.
     *
     * @return object|null The authenticated user, otherwise null.
     */
    public function getUser(): ?object
    {
        return self::$__api_user;
    }

    /**
     * Set the authenticated user.
     *
     * If no model is provided, the user from the authorization config is used.
     *
     * @param  mixed  $model
     * @return $this
     */
    public function setUser(mixed $model = null): self
    {
        if (isset($model)) $model = \is_object($model) ? $model : app($model);
        self::$__api_user = $model ?? app($this->authorizationConfig()['model']);
        return $this;
    }

    /**
     * Get the authenticated user.
     *
     * @return object|null The authenticated user, otherwise null.
     */
    public function user(mixed $conditionals): ?object
    {
        return $this->setUser($this->getUser()->conditionals($conditionals)->first() ?? null);
    }
}
