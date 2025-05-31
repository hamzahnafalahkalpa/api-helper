<?php

namespace Hanafalah\ApiHelper\Schemas;

use Hanafalah\ApiHelper\Contracts\Schemas\ApiAccess as ContractsApiAccess;
use Hanafalah\ApiHelper\Data\ApiAccessData;
use Hanafalah\ApiHelper\Supports\BaseApiAccess;
use Illuminate\Database\Eloquent\Model;

class ApiAccess extends BaseApiAccess implements ContractsApiAccess
{
    protected string $__entity = 'ApiAccess';
    public static $api_access_model;

    public function prepareStoreApiAccess(ApiAccessData $api_access_dto): Model{
        if (isset($api_access_dto->id)){
            $guard = ['id' => $api_access_dto->id];
        }else{
            if (!isset($api_access_dto->app_code)) throw new \Exception('app_code is required');
            $guard = ['app_code' => $api_access_dto->app_code];
        }
        $api_access = $this->apiAccess()->updateOrCreate($guard,[
            'reference_type' => $api_access_dto->reference_type,
            'reference_id'   => $api_access_dto->reference_id,
        ]);
        foreach ($api_access_dto->props->toArray() as $key => $value) {
            $api_access->{$key} = $value;
        }
        $api_access->save();
        return static::$api_access_model = $api_access;
    }
}
