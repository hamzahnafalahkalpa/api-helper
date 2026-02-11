<?php

namespace Hanafalah\ApiHelper\Validators;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Hanafalah\ApiHelper\{
    Exceptions
};
use Hanafalah\ApiHelper\Facades\ApiAccess;

class JWTTokenValidator extends Environment
{
    private $auth;

    public function handle(): bool
    {
        $profiling = config('micro-tenant.profiling.enabled', false);
        $timings = [];

        $this->auth = ApiAccess::getDecoded();
        if ($this->isForToken()) {
            $t = $profiling ? microtime(true) : 0;
            $this->authenticate();
            if ($profiling) $timings['authenticate'] = round((microtime(true) - $t) * 1000, 2);
        } else {
            $t = $profiling ? microtime(true) : 0;
            $this->tokenValidator();
            if ($profiling) $timings['tokenValidator'] = round((microtime(true) - $t) * 1000, 2);
        }

        if ($profiling && !empty($timings)) {
            \Illuminate\Support\Facades\Log::info('[JWTTokenValidator::handle Breakdown]', $timings);
        }

        return true;
    }

    public function tokenValidator(): self
    {
        $profiling = config('micro-tenant.profiling.enabled', false);
        $timings = [];

        if (!Auth::check()) {
            // OPTIMIZATION: First try to use the already-validated access token
            // This avoids expensive bcrypt password verification on every request
            $t = $profiling ? microtime(true) : 0;
            $accessToken = ApiAccess::getAccessToken();
            if ($profiling) $timings['get_access_token'] = round((microtime(true) - $t) * 1000, 2);

            if ($accessToken && $accessToken->tokenable) {
                // Access token exists and has a user - use it directly (fast path)
                $t = $profiling ? microtime(true) : 0;
                Auth::login($accessToken->tokenable);
                if ($profiling) $timings['auth_login_from_token'] = round((microtime(true) - $t) * 1000, 2);
            } else {
                // Fallback to JWT data authentication (slow path - only for initial login)
                $data = $this->auth->data ?? null;

                if (!$data) {
                    throw new \Exception('Auth data is missing');
                }

                if (isset($data->id)) {
                    $t = $profiling ? microtime(true) : 0;
                    $user = $this->UserModel()->findOrFail($data->id);
                    if ($profiling) $timings['user_find'] = round((microtime(true) - $t) * 1000, 2);

                    $t = $profiling ? microtime(true) : 0;
                    Auth::login($user);
                    if ($profiling) $timings['auth_login'] = round((microtime(true) - $t) * 1000, 2);
                } else {
                    if (!is_string($data->username ?? null) || !is_string($data->password ?? null)) {
                        throw new \Exception('Invalid username or password format');
                    }
                    $t = $profiling ? microtime(true) : 0;
                    Auth::attempt([
                        "username" => $data->username,
                        "password" => $data->password
                    ]);
                    if ($profiling) $timings['auth_attempt'] = round((microtime(true) - $t) * 1000, 2);
                }
            }

            if ($profiling && !empty($timings)) {
                \Illuminate\Support\Facades\Log::info('[JWTTokenValidator::tokenValidator Breakdown]', $timings);
            }
        }

        return $this;
    }


    /**
     * Validates the token of the current instance.
     *
     * @return self
     *
     * @throws \Hanafalah\ApiHelper\Exceptions\InvalidUsernameOrPassword
     */
    public function authenticate(): self
    {
        $this->user(function ($q) {
            foreach ($this->authorizationConfig()['keys'] as $key) {
                if (!isset($this->auth->data->{$key})) throw new Exception($key . ' not found in user data');
                $q->where($key, $this->auth->data->{$key});
            }
        });
        $validation = isset($this->__api_user) && $this->checkingPassword();
        $validation = $this->additionalChecking($validation);
        if (!$validation) throw new Exceptions\InvalidUsernameOrPassword();
        return $this;
    }

    /**
     * Check if the given password matches the given hash.
     *
     * @param string $password The password to check.
     * @param string $hash The hash to compare with.
     *
     * @return bool True if the password matches the hash, otherwise false.
     */
    protected function checkingPassword(?string $password = null, ?string $hash = null): bool
    {
        $passName = $this->authorizationConfig()['password'];
        $password ??= $this->auth->data->{$passName};
        $hash     ??= $this->__api_user->{$passName};
        return Hash::check($password, $hash);
    }

    /**
     * Additional checking for the token.
     *
     * This function checks if the token have additional data, if yes then it will
     * check if the additional data is match with the additional data in the
     * api_access table.
     *
     * @param mixed $decoded_token The decoded token.
     * @param bool $validation The validation status.
     *
     * @return bool The validation status.
     */
    private function additionalChecking(bool $validation): bool
    {
        $api_access = $this->getApiAccess();
        if (isset($api_access->additional)) {
            foreach ($api_access->additional as $key => $value) {
                if (!isset($this->auth->data->{$key})) throw new Exceptions\UnauthorizedAccess;
                $validation &= $value == $this->auth->data->{$key};
                if (!$validation) throw new Exceptions\UnauthorizedAccess;
            }
        }
        return $validation;
    }
}
