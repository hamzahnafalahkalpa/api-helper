<?php

namespace Zahzah\ApiHelper\Commands;

use Zahzah\ApiHelper\Concerns\ApiAccessPrompt;
use Zahzah\ApiHelper\Facades\ApiAccess;
use Zahzah\ApiHelper\Schemas\ApiAccess as SchemasApiAccess;
use Illuminate\Support\Str;

class ApiAccessMakeCommand extends EnvironmentCommand
{
    use ApiAccessPrompt;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'helper:generate {--app-code=} {--algorithm=} {--reference-id=} {--reference-type=}';

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
    public function handle(){
       $this->generate(); 
    }

    protected function generate(){
        $algorithm = $this->chooseAlgorithm();
        $isRsa     = $this->inArray($algorithm, ['RS256', 'RS384', 'RS512']);
        $isEdDsa   = $this->inArray($algorithm, ['ES256', 'ES384', 'ES512']);
        if ($isRsa || $isEdDsa) $this->info('Key will generate later');
                
        $isHSA = $this->inArray($algorithm, ['HS256', 'HS384', 'HS512']);
        $props = [
            'algorithm'  => $algorithm,
        ];
        if ($isHSA) {
            $this->info('Secret key generated');
            $props['secret'] = Str::random(32);
        }

        $attributes = [
            'app_code'   => $this->askAppCode(),
            ...$this->askOtherProperties()
        ];
        $attributes['props'] = [
            ...$props,
            ...$attributes['props']
        ];
        $apiAccess  = ApiAccess::useSchema(SchemasApiAccess::class)->add($attributes)->getModel();
        if (isset($apiAccess)){
            if ($isRsa || $isEdDsa){
                $this->call('helper:generate-key',[
                    '--app-code'         => $apiAccess->app_code,
                    '--algorithm'        => $algorithm,
                    '--reference-id'     => $this->option('reference-id'),
                    '--reference-type'   => $this->option('reference-type'),
                ]);
                // $this->askGeneratePublicKey();
                // if ($this->getAskGeneratePublicKeyResult()){
                // }
            }
        }
    }
}
