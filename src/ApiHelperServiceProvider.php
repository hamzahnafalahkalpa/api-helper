<?php

declare(strict_types=1);

namespace Hanafalah\ApiHelper;

use Laravel\Sanctum\Sanctum;
use Hanafalah\ApiHelper\Schemas\ApiAccess as SchemaApiAccess;
use Hanafalah\LaravelSupport\Providers\BaseServiceProvider;

class ApiHelperServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->registerMainClass(ApiHelper::class)
                ->registerCommandService(Providers\CommandServiceProvider::class)
                ->registers([
                    '*',
                    'Services' => function () {
                        $this->binds([
                            Contracts\ModuleApiAccess::class => ApiAccess::class,
                            Contracts\ApiHelper::class => ApiHelper::class
                        ]);
                    }
                ]);
    }

    /**
     * Boot the service provider
     *
     * @return void
     */
    public function boot()
    {
        Sanctum::usePersonalAccessTokenModel($this->PersonalAccessTokenModelInstance());
    }

    /**
     * Get the base directory of the package.
     *
     * @return string
     */
    protected function dir(): string
    {
        return __DIR__ . '/';
    }
}
