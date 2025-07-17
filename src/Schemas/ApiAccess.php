<?php

namespace Hanafalah\ApiHelper\Schemas;

use Hanafalah\ApiHelper\Contracts\Schemas\ApiAccess as ContractsApiAccess;
use Hanafalah\ApiHelper\Data\ApiAccessData;
use Hanafalah\ApiHelper\Supports\BaseApiAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ApiAccess extends BaseApiAccess implements ContractsApiAccess
{
    protected string $__entity = 'ApiAccess';
    public static $api_access_model;

    protected function viewUsingRelation(): array{
        return [];
    }

    protected function showUsingRelation(): array{
        return ['reference'];
    }

    public function getApiAccess(): mixed{
        return static::$api_access_model;
    }

    public function prepareShowApiAccess(?Model $model = null, ? array $attributes = null): ?Model{
        $attributes ??= request()->all();

        $model ??= $this->getApiAccess();
        if (!isset($model)){
            $id = request()->id;
            if (!isset($id)) throw new \Exception('No id provided', 422);
            $model = $this->apiAccess()->with($this->showUsingRelation())->findOrFail($id);
        }else{
            $model->load($this->showUsingRelation());
        }
        return static::$api_access_model = $model;
    }

    public function showApiAccess(?Model $model = null): array{
        return $this->showEntityResource(function() use ($model){
            return $this->prepareShowApiAccess($model);
        });
    }

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

    public function storeApiAccess(? ApiAccessData $api_access_dto): array{
        return $this->transaction(function() use ($api_access_dto){
            return $this->showApiAccess($this->prepareStoreApiAccess($api_access_dto ?? ApiAccessData::from(request()->all())));
        });
    }

    public function apiAccess(mixed $conditionals = null): Builder{
        $this->booting();
        return $this->ApiAccessModel()->withParameters()->conditionals($this->mergeArray($this->conditionals ?? []));
    }
}
