<?php

declare(strict_types=1);

namespace Zahzah\ApiHelper;

use Laravel\Sanctum\Sanctum;
use Zahzah\ApiHelper\Schemas\ApiAccess as SchemaApiAccess;
use Zahzah\LaravelSupport\Providers\BaseServiceProvider;

class ApiHelperServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->registerMainClass(ApiHelper::class)
             ->registerCommandService(Providers\CommandServiceProvider::class)
            ->registers([
                '*','Services' => function (){
                    $this->binds([
                        Contracts\ApiHelper::class => new ApiHelper(),
                        Contracts\ApiAccess::class => function($app){
                            return ApiAccess::class;
                        },
                        Schemas\ApiAccess::class => new SchemaApiAccess
                    ]);
                }
            ]);
    }

    /**
     * Boot the service provider
     *
     * @return void
     */
    public function boot(){
        Sanctum::usePersonalAccessTokenModel($this->PersonalAccessTokenModelInstance());
    }

    /**
     * Get the base directory of the package.
     *
     * @return string
     */
    protected function dir(): string{
        return __DIR__.'/';
    }
}
