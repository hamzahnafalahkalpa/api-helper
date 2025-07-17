<?php

namespace Hanafalah\ApiHelper\Commands;

use Hanafalah\ApiHelper\Concerns\ApiAccessPrompt;
use Hanafalah\ApiHelper\Data\ApiAccessData;
use Hanafalah\ApiHelper\Data\ApiAccessPropsData;
use Hanafalah\ApiHelper\Facades\ApiAccess;
use Hanafalah\ApiHelper\Schemas\ApiAccess as SchemasApiAccess;
use Illuminate\Support\Str;

class ApiAccessMakeCommand extends EnvironmentCommand
{
    use ApiAccessPrompt;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'helper:generate {--app-code=} {--algorithm=} {--reference-id=} {--reference-type=} {--secret=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Api Access';

    /**
     * Execute the console command.
     * 
     * @return mixed
     */
    public function handle()
    {
        $this->generate();
    }

    protected function generate()
    {
        $algorithm = $this->chooseAlgorithm($this->option('algorithm') ?? null);
        $isRsa     = $this->inArray($algorithm, ['RS256', 'RS384', 'RS512']);
        $isEdDsa   = $this->inArray($algorithm, ['ES256', 'ES384', 'ES512']);
        if ($isRsa || $isEdDsa) $this->info('Key will generate later');

        $isHSA = $this->inArray($algorithm, ['HS256', 'HS384', 'HS512']);
        $props = [
            'algorithm'  => $algorithm,
        ];
        if ($isHSA) {
            $this->info('Secret key generated');
            $props['secret'] = $this->option('secret') ?? Str::random(32);
        }

        $appCodeOption = $this->option('app-code');
        if (isset($appCodeOption)) {
            $attributes = [
                'app_code' => $appCodeOption,
            ];
        } else {
            $attributes = [
                'app_code' => $this->askAppCode(),
                ...$this->askOtherProperties()
            ];
        }

        $attributes['props'] = [
            ...$props,
            ...$attributes['props'] ?? []
        ];
        $apiAccess = app(config('app.contracts.ApiAccess'))->prepareStoreApiAccess(ApiAccessData::from([
            'app_code'       => $attributes['app_code'],
            'reference_type' => $this->option('reference-type'),
            'reference_id'   => $this->option('reference-id'),
            'props'          => ApiAccessPropsData::from($attributes['props']),
        ]));
        if (isset($apiAccess)) {
            if ($isRsa || $isEdDsa) {
                $this->call('helper:generate-key', [
                    '--app-code'         => $apiAccess->app_code,
                    '--algorithm'        => $algorithm,
                    '--reference-id'     => $this->option('reference-id'),
                    '--reference-type'   => $this->option('reference-type')
                ]);
            }
        }
    }
}
