<?php

namespace Hanafalah\ApiHelper\Schemas;

use Hanafalah\ApiHelper\{
    Supports\BaseApiAccess,
    Contracts\Schemas\Token as TokenInterface
};
use Hanafalah\ApiHelper\Exceptions\UnauthorizedAccess;
use Hanafalah\ApiHelper\Facades\ApiAccess;

class Token implements TokenInterface
{
    protected $data;

    public function __construct()
    {
        $this->data = ApiAccess::authorizing()->handle();
    }

    public function handle()
    {
        if (ApiAccess::isForToken()) {
            if (config('api-helper.single-login',true)){
                $user = auth()->user();
                if (!isset($user)) throw new UnauthorizedAccess();
                $user->token()->where([                    
                    'name'           => ApiAccess::getTokenAccessName(),
                    'device_id'      => $_SERVER['HTTP_DEVICE_ID'] ?? null
                ])->delete();
            }
            return ApiAccess::encrypting(ApiAccess::getDecoded()->data);
        }
        if (ApiAccess::isForAuthenticate()) {
            if (request()->headers->has('Authorization')) {
                // $decoded    = $this->getDecoded();
                $decoded    = ApiAccess::getDecoded();
                $access_token = ApiAccess::getAccessToken();
                $validation = isset($access_token);
                if ($validation) {
                    //IF JTI EXISTS
                    if (isset($decoded->jti) || isset($access_token->jti)) {
                        $validation = isset($decoded->jti) && $decoded->jti == $access_token->jti;
                    }
                    $user = $access_token->tokenable;
                    $validation &= isset($user);
                    return $validation;
                }
                throw new UnauthorizedAccess;
            }
            return false;
        }
    }
}
