<?php

namespace Zahzah\ApiHelper\Concerns;

use Illuminate\Support\Str;
use Zahzah\LaravelHasProps\Concerns\HasProps;

trait ApiAccessPrompt{
    protected $__ask_generate_public_key, $__ask_generate_api_access;

    public function askAppCode(){
        return $this->option('app-code')   ?: $this->ask('Enter app code ?');
    }

    protected function askGeneratePublicKey(): self{
        $answer = ($this->option('algorithm')) 
                    ? Str::startsWith($this->option('algorithm'), 'RS')
                    : $this->confirm('Do you want to generate public key (RSA Condition) ?',false);
        $this->__ask_generate_public_key = $answer;
        return $this;
    }

    protected function askGenerateApiAccess(): bool{
        $answer = $this->confirm('Do you want to generate api access ?',false);
        $this->__ask_generate_api_access = $answer;
        return $this->__ask_generate_api_access;
    }

    protected function askOtherProperties(){
        $properties = [HasProps::getDataColumn() => ['additional' => []]];
        do {
            $propName = $this->ask('Enter new properties name or blank it ?');
            $newProp  = isset($propName);
            if ($newProp){
                $propValue = $this->ask('Enter value of property ?');
                $this->info("{$propName} => {$propValue}");
                $properties[HasProps::getDataColumn()]['additional'][$propName] = $propValue;
            }
        } while ($newProp);
        return $properties;
    }    

    protected function chooseAlgorithm(? string $algorithm = null,? string $family = null) {
        if (!isset($algorithm)) {
            $algorithms = [
                'RS256', 'RS384', 'RS512',
                'HS256', 'HS384', 'HS512',
                'ES256', 'ES384', 'ES512',
                // 'PS256', 'PS384', 'PS512',
            ];

            if (isset($family)) {
                $algorithms = array_filter($algorithms, function ($value) use ($family) {
                    return strncmp($value, $family, 2) === 0;
                });
            }

            $algorithm = $this->choice(
                'Pilih Algoritma',
                $algorithms,
                'RS256'
            );
        }
        return $algorithm;
    }
}

