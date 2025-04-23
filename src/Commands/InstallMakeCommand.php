<?php

namespace Hanafalah\ApiHelper\Commands;

use Hanafalah\ApiHelper\Concerns\ApiAccessPrompt;

class InstallMakeCommand extends EnvironmentCommand
{
    use ApiAccessPrompt;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'helper:install';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command ini digunakan untuk installing awal api helper';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $provider = 'Hanafalah\ApiHelper\ApiHelperServiceProvider';

        $this->comment('Installing ApiHelper...');
        $this->callSilent('vendor:publish', [
            '--provider' => $provider,
            '--tag'      => 'config'
        ]);
        $this->info('✔️  Created config/api-helper.php');

        $this->callSilent('vendor:publish', [
            '--provider' => $provider,
            '--tag'      => 'providers'
        ]);

        $this->info('✔️  Created ApiHelperServiceProvider.php');

        $this->callSilent('vendor:publish', [
            '--provider' => $provider,
            '--tag'      => 'migrations'
        ]);

        $this->info('✔️  Created migrations');

        $this->askingGenerateApiAccess();

        $this->comment('hanafalah/api-helper installed successfully.');
    }

    protected function askingGenerateApiAccess()
    {
        if ($this->askGenerateApiAccess()) {
            $this->info('✔️  Generate Key');
            $this->call('helper:generate');
            $this->info('✔️  Generated Key');
        }
    }
}
