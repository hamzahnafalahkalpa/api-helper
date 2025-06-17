<?php

namespace Hanafalah\ApiHelper\Supports;

use Hanafalah\ApiHelper\{
    Exceptions,
    Concerns
};
use Hanafalah\ApiHelper\Contracts\Supports\BaseApiAccess as SupportsBaseApiAccess;
use Hanafalah\LaravelSupport\Concerns\{
    Support as ConcernSupport,
    DatabaseConfiguration as DatabaseConcernSupport
};
use Hanafalah\LaravelSupport\Concerns\Support\ErrorHandling;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;
use Hanafalah\LaravelSupport\Supports\PackageManagement;


class BaseApiAccess extends PackageManagement implements DataManagement, SupportsBaseApiAccess
{
    use Concerns\HasHeader,
        Concerns\HasEncryptor,
        Concerns\HasCounter,
        Concerns\HasInit,
        Concerns\HasWorkspace,
        Concerns\HasSetup,
        Concerns\HasToken,
        Concerns\InitConfig,
        Concerns\InitUser,
        ErrorHandling,
        ConcernSupport\HasResponse,
        DatabaseConcernSupport\HasModelConfiguration;

    const FOR_TOKEN        = 'FOR_TOKEN';
    const FOR_AUTHENTICATE = 'FOR_AUTHENTICATE';

    protected static $__access_token;

    public static $__generated_token = [
        'token' => null,
        'expires_at' => null
    ];
    protected static $__reason;
    protected $__threshold = 1800;


    public function __construct()
    {
        if ($this->notReady()) {
            $this->initialized();

            $this->setLocalConfig('api-helper');
            self::$__api_helper_config = $this->__local_config;
            $this->setup();
            $this->setUser();
        }
    }

    /**
     * Set API access by application code.
     *
     * @throws \Exceptions\AppNotFoundException
     * @return self
     */
    public function setApiAccessByAppCode(): self
    {
        $api_access = $this->getApiAccess()->select([
            'id',
            'app_code',
            'props',
            'reference_type',
            'reference_id'
        ])->findAppCode(static::$__app_code)->first();
        if (!isset($api_access)) throw new Exceptions\AppNotFoundException;
        $this->setApiAccess($api_access);
        return $this;
    }

    /**
     * Set API access by username.
     *
     * @throws \Exceptions\AppNotFoundException
     * @return self
     */
    protected function setApiAccessByUsername(): self
    {
        static::$__username = static::$__headers->get('Username');
        $api_access = static::getApiAccess()->findUsername(static::$__headers->get('Username'))->first();
        if (!isset($api_access)) throw new Exceptions\AppNotFoundException;
        static::setApiAccess($api_access);
        return $this;
    }

    /**
     * Sets API access by token.
     *
     * @throws \Exceptions\TokenMistmatchException
     * @return self
     */
    protected function setApiAccessByToken(): self
    {
        $api_access = $this->getApiAccess()->findToken($this->getToken())->first();
        if (!isset($api_access)) throw new Exceptions\TokenMistmatchException;
        $this->setApiAccess($api_access);
        return $this;
    }

    /**
     * Set the reason to generate token.
     *
     * @return self
     */
    public function forToken(): self
    {
        self::$__reason = self::FOR_TOKEN;
        return $this;
    }

    public function forAuthenticate(): self
    {
        self::$__reason = self::FOR_AUTHENTICATE;
        return $this;
    }

    /**
     * Checks if the token is being generated for an API access.
     *
     * @return bool True if the token is being generated for an API access, false otherwise.
     */
    public function isForToken(): bool
    {
        return self::$__reason === self::FOR_TOKEN;
    }

    public function isForAuthenticate(): bool
    {
        return self::$__reason === self::FOR_AUTHENTICATE;
    }

    /**
     * Get the reason the token is being generated.
     *
     * @return string One of the self::REASON_ constants.
     */
    public function getReason(): string
    {
        return self::$__reason;
    }

    /**
     * Get the JTI of the token if it exists.
     *
     * @return string|null
     */
    public function getJti(): ?string
    {
        return self::$__generated_token['jti'] ?? null;
    }

    /**
     * Set the expiration time of the token in the database.
     *
     * @return self
     */
    public function setExpirationToken(mixed $expiration = null): self
    {
        self::$__generated_token['expires_at'] = $expiration;
        return $this;
    }
}
