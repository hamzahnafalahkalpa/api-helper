<?php

namespace Hanafalah\ApiHelper\Concerns;

use Hanafalah\ApiHelper\Exceptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait HasInit
{
    /**
     * Initialize the API access by token.
     *
     * @throws \Exceptions\UnauthorizedAccess
     * @throws \Exceptions\MissingModelCodeException
     * @return self
     */
    protected function initByToken(): self
    {
        if (!$this->__authorization) throw new Exceptions\UnauthorizedAccess;

        // If __access_token is null (JWT token without '|'), fallback to initByAppCode
        if ($this->__access_token === null) {
            if ($this->hasAppCode()) {
                return $this->initByAppCode();
            }
            throw new Exceptions\UnauthorizedAccess;
        }

        $this->setHeader('AppCode', $this->__access_token->app_code);
        $this->initByAppCode();
        return $this;
    }

    /**
     * Initialize the API access by username.
     *
     * @throws \Exceptions\UnauthorizedAccess
     * @return self
     */
    protected function initByUsername(): self
    {
        $authorization = $this->setApiAccessByUsername()
            ->setAppCode($this->getApiAccess()->app_code)
            ->decrypting($this->getHeader('AppKey'));
        // $this->setHeader('AppKey',$authorization);
        // $this->authorizing();
        return $this;
    }

    /**
     * Initialize the API access by app code.
     *
     * @throws \Exceptions\UnauthorizedAccess
     * @return self
     */
    protected function initByAppCode(): self
    {
        $authorization = $this->setAppCode($this->getHeader('AppCode'))
            ->setApiAccessByAppCode()
            ->decrypting($this->__authorization);
        if (!$authorization) throw new Exceptions\UnauthorizedAccess;
        return $this;
    }
}
