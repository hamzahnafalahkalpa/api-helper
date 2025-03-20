<?php

namespace Zahzah\ApiHelper\Commands;

use Zahzah\LaravelSupport\Concerns\ServiceProvider\HasMigrationConfiguration;

class EnvironmentCommand extends \Zahzah\LaravelSupport\Commands\BaseCommand{
    use HasMigrationConfiguration;

    protected function init(): self{
        //INITIALIZE SECTION
        $this->setLocalConfig('api-helper');
        return $this;
    }

    protected function dir(): string{
        return __DIR__.'/../';
    }
}
