<?php

namespace Hanafalah\ApiHelper\Concerns;

use Hanafalah\ApiHelper\{
    Exceptions
};

use Hanafalah\LaravelHasProps\Concerns\HasProps;

trait HasSetup
{
    protected static $__api_access;
    protected string
        $__username,
        $__timestamp,
        $__req_username,
        $__req_password,
        $__req_secret,
        $__app_code,
        $__app_key;
    protected array $__injectors = [];

    //GETTER SECTION
    /**
     * Retrieves the app key associated with the current instance.
     *
     * @return string The app key associated with the current instance.
     */
    protected function getAppKey(): string
    {
        return $this->__app_key;
    }

    /**
     * Retrieves the timestamp associated with the current instance.
     *
     * @return int|string The timestamp associated with the current instance.
     */
    protected function getTimestamp(): int|string
    {
        return $this->__timestamp;
    }

    /**
     * Retrieves the token associated with the current instance.
     *
     * @return string The token associated with the current instance.
     */
    protected function setTimestamp($timestamp): self
    {
        $this->__timestamp = $timestamp;
        return $this;
    }

    /**
     * Returns the app code that is currently set.
     *
     * @return string|null
     */
    protected function getAppCode(): ?string
    {
        return $this->__app_code;
    }

    /**
     * Get the API access object, creating a new one if it doesn't exist.
     *
     * @return mixed
     */
    public function getApiAccess(): mixed
    {
        return self::$__api_access ?? $this->ApiAccessModel();
    }

    /**
     * Sets the app code for the current instance.
     *
     * If $app_code is null, it will default to the app code of the current
     * API access object.
     *
     * @param string|null $app_code description of the app code
     * @return self
     */
    public function setAppCode(?string $app_code = null): self
    {
        $this->__app_code = $app_code ?? request()->header('AppCode') ?? request()->AppCode ?? self::$__api_access->app_code;
        return $this;
    }

    /**
     * Set the API access and app code.
     *
     * @param datatype $api_access description of the API access
     * @return mixed
     */
    protected function setApiAccess($api_access): mixed
    {
        self::$__api_access = $api_access;
        return static::class;
    }

    protected function setInjector(mixed $data): self
    {
        (is_array($data))
            ? $this->__injectors = array_merge($this->__injectors, $data)
            : $this->__injectors[] = $data;

        return $this;
    }

    /**
     * Returns the username from the AppKey header.
     *
     * @return string
     */
    protected function getReqUsername(): string
    {
        return $this->__req_username;
    }

    /**
     * Returns the secret key from the AppKey header.
     *
     * @return string
     */
    protected function getReqSecret(): string
    {
        return $this->__req_secret;
    }

    protected function getReqPassword(): string
    {
        return $this->__req_password;
    }
}
