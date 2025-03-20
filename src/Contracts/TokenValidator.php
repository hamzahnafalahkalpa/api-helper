<?php

namespace Hanafalah\ApiHelper\Contracts;

interface TokenValidator
{
    public function handle(): bool;
}
