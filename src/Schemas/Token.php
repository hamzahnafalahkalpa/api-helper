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
    protected static array $profilingTimings = [];

    public function __construct()
    {
        $profiling = config('micro-tenant.profiling.enabled', false);

        $t = $profiling ? microtime(true) : 0;
        $this->data = ApiAccess::authorizing()->handle();
        if ($profiling) {
            static::$profilingTimings['authorizing_handle'] = round((microtime(true) - $t) * 1000, 2);
        }
    }

    public function handle()
    {
        $profiling = config('micro-tenant.profiling.enabled', false);

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
                $t = $profiling ? microtime(true) : 0;
                $decoded    = ApiAccess::getDecoded();
                $access_token = ApiAccess::getAccessToken();
                $validation = isset($access_token);
                if ($validation) {
                    //IF JTI EXISTS
                    if (isset($decoded->jti) || isset($access_token->jti)) {
                        $validation = isset($decoded->jti) && $decoded->jti == $access_token->jti;
                    }

                    $t2 = $profiling ? microtime(true) : 0;
                    $user = $access_token->tokenable;
                    if ($profiling) {
                        static::$profilingTimings['tokenable_load'] = round((microtime(true) - $t2) * 1000, 2);
                    }

                    $validation &= isset($user);

                    if ($profiling) {
                        static::$profilingTimings['validation_total'] = round((microtime(true) - $t) * 1000, 2);
                        \Illuminate\Support\Facades\Log::info('[Token::handle Breakdown]', static::$profilingTimings);
                        static::$profilingTimings = []; // Reset for next request
                    }
                    return $validation;
                }
                throw new UnauthorizedAccess;
            }
            return false;
        }
    }
}
