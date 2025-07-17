<?php

namespace Hanafalah\ApiHelper\Commands;

use Hanafalah\LaravelSupport\Concerns\ServiceProvider\HasMigrationConfiguration;

class EnvironmentCommand extends \Hanafalah\LaravelSupport\Commands\BaseCommand
{
    use HasMigrationConfiguration;

    protected function init(): self
    {
        //INITIALIZE SECTION
        $this->setLocalConfig('api-helper');
        return $this;
    }

    protected function dir(): string
    {
        return __DIR__ . '/../';
    }
}
