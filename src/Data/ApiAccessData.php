<?php

namespace Hanafalah\ApiHelper\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class ApiAccessData extends Data{
    #[MapInputName('id')]
    #[MapName('id')]
    public mixed $id = null;

    #[MapInputName('app_code')]
    #[MapName('app_code')]
    public mixed $app_code;

    #[MapInputName('reference_type')]
    #[MapName('reference_type')]
    public ?string $reference_type = null;

    #[MapInputName('reference_id')]
    #[MapName('reference_id')]
    public mixed $reference_id = null;

    #[MapInputName('props')]
    #[MapName('props')]
    public ? ApiAccessPropsData $props = null;
}