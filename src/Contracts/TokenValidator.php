<?php

namespace Zahzah\ApiHelper\Contracts;

interface TokenValidator{
    public function handle(): bool;
}