<?php

namespace Hanafalah\ApiHelper\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class ApiAccessPropsData extends Data{
    #[MapInputName('algorithm')]
    #[MapName('algorithm')]
    public string $algorithm;

    #[MapInputName('secret')]
    #[MapName('secret')]
    public ?string $secret = null;

    #[MapInputName('additional')]
    #[MapName('additional')]
    public array $additional = [];
}