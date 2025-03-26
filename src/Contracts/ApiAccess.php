<?php

namespace Hanafalah\ApiHelper\Contracts;

interface ApiAccess
{
    public function __construct();
    // public function getModelCode();
    public function getToken();
    public function encrypting($data);
    public function init();
    public function generateToken(?callable $callback = null): string;
    // public function response($callback);
}
