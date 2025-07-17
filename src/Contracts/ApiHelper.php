<?php

namespace Hanafalah\ApiHelper\Contracts;

use Illuminate\Http\Request as HttpRequest;

interface ApiHelper
{
    public function scope($class, callable $callable, $alias = null): self;
    public function scopeWhen(bool $condition, $class, callable $callable, $alias = null): self;
    public function newRequest($add = [], $classRequest = null): HttpRequest;
    public function results(): array;
}
