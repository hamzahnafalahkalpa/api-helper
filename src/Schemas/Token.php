<?php

namespace Hanafalah\ApiHelper\Schemas;

use Hanafalah\ApiHelper\{
    Supports\BaseApiAccess,
    Contracts\Schemas\Token as TokenInterface
};
use Hanafalah\ApiHelper\Exceptions\UnauthorizedAccess;

class Token extends BaseApiAccess implements TokenInterface
{
    protected $data;

    public function __construct()
    {
        parent::__construct();
        $this->data = $this->authorizing()->handle();
    }

    public function handle()
    {
        if ($this->isForToken()) {
            $this->getUser()->token()->where([
                'name'           => $this->__token_access_name,
                'device_id'      => $_SERVER['HTTP_DEVICE_ID'] ?? null
            ])->delete();
            
            return $this->encrypting(self::$__decode_result->data);
        }
        if ($this->isForAuthenticate()) {
            if (request()->headers->has('Authorization')) {
                $decoded    = $this->getDecoded();
                $validation = isset(self::$__access_token);
                if ($validation) {
                    //IF JTI EXISTS
                    if (isset($decoded->jti) || isset(self::$__access_token->jti)) {
                        $validation = isset($decoded->jti) && $decoded->jti == self::$__access_token->jti;
                    }
                    $user = self::$__access_token->tokenable;
                    (isset($user))
                        ? $this->setUser($user)
                        : $validation = false;
                    return $validation;
                }
                throw new UnauthorizedAccess;
            }
            return false;
        }
    }
}
