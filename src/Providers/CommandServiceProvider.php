<?php

declare(strict_types=1);

namespace Hanafalah\ApiHelper\Providers;

use Illuminate\Support\ServiceProvider;
use Hanafalah\ApiHelper\Commands as Commands;

class CommandServiceProvider extends ServiceProvider
{
    private $commands = [
        Commands\GenerateRsKeyCommand::class,
        Commands\InstallMakeCommand::class,
        Commands\ApiAccessMakeCommand::class
    ];

    /**
     * Registers the commands.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(config('api-helper.commands', $this->commands));
    }

    public function provides()
    {
        return $this->commands;
    }
}
