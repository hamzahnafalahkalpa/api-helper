<?php

namespace Hanafalah\ApiHelper\Concerns;

/**
 * Concern for retrieving data from the HTTP request headers.
 */
trait HasHeader
{
    protected static $__headers;

    protected $__authorization;

    /**
     * Set the headers for the PHP function.
     *
     * @return self
     */
    protected function setCollectHeader(): self
    {
        static::$__headers = request()->headers;
        return $this;
    }

    /**
     * Get the value from the headers.
     *
     * @param string $key
     *
     * @return string|null
     */
    protected function getHeader(string $key): string|null
    {
        return static::$__headers->get($key);
    }

    /**
     * Set the value for the specific header key.
     *
     * @param string $key
     * @param string $value
     *
     * @return self
     */
    protected function setHeader(string $key, string $value): self
    {
        static::$__headers->set($key, $value);
        return $this;
    }

    /**
     * Check if the header key exists in the headers.
     *
     * @param string $name
     *
     * @return bool
     */
    protected function hasHeader(string $name): bool
    {
        return static::$__headers->has($name);
    }

    /**
     * Check if the object has a token in the headers.
     *
     * @return bool
     */
    protected function hasAppCode(): bool
    {
        return $this->hasHeader('AppCode');
    }

    protected function hasToken(): bool
    {
        return $this->hasHeader('Authorization');
    }

    /**
     * Check if the object has a token in the headers.
     *
     * @return bool
     */
    protected function hasUsername(): bool
    {
        return $this->hasHeader('Username');
    }

    /**
     * Generate a timestamp value based on the current time.
     *
     * The formula used is: time() - strtotime('1970-01-01 00:00:00')
     *
     * @return int
     */
    protected function generateTimestamp()
    {
        return time() - strtotime('1970-01-01 00:00:00');
    }
}
