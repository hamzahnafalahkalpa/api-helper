<?php

namespace Zahzah\ApiHelper\Concerns\Token;

use DateTimeInterface;
use Laravel\Sanctum\HasApiTokens as SanctumHasApiTokens;
use Laravel\Sanctum\NewAccessToken;

trait HasApiTokens{
    use SanctumHasApiTokens;

    /**
     * Create a new personal access token for the user.
     *
     * @param  string  $name
     * @param  array  $abilities
     * @param  \DateTimeInterface|null  $expiresAt
     * @return \Laravel\Sanctum\NewAccessToken
     */
    public function createToken(string $name, array $abilities = ['*'], DateTimeInterface $expiresAt = null)
    {
        // $time = time();
        // $data = [];
        // $auth_model = config('api-helper.authorization_model');
        // if (!$auth_model['model'] instanceof self) throw new Exception("Model of `authorization_model` on `api-helper` config is not same with this model", 1);
        
        // foreach ($auth_model['keys'] as $column) $data[$column] = $this->{$column};
                
        // $api_access = ApiAccess::forToken()->setAppCode();
        // $token = $api_access->encrypting($data);
        // return $this->setToken($name, $token, $abilities, $expiresAt);
    }

    public function setToken(string $name,array $data, array $abilities = ['*'], $expiresAt = null): NewAccessToken
    {
        $token = $this->token()->updateOrCreate([
            'tokenable_type' => $this->getMorphClass(),
            'tokenable_id'   => $this->getKey(),
            'name'           => $name,
            'device_id'      => $_SERVER['HTTP_DEVICE_ID'] ?? null
        ],[
            'token'      => hash('sha256', $data['plainTextToken']),
            'abilities'  => $abilities,
            'expires_at' => $expiresAt
        ]);

        if (count($data['props']) > 0){
            foreach ($data['props'] as $key => $prop) $token->{$key} = $prop;
            $token->save();
        }
        return new NewAccessToken($token, $token->getKey().'|'.$data['plainTextToken']);
    }

    public function token(){return $this->morphOneModel('PersonalAccessToken', 'tokenable');}

}