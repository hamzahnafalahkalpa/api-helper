<?php

declare(strict_types=1);

namespace Hanafalah\ApiHelper;

use Laravel\Sanctum\Sanctum;
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
                            Contracts\Encryptions\EncryptorInterface::class => Encryptions\Environment::class,
                            Contracts\Validators\TokenValidator::class => Validators\Environment::class
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
