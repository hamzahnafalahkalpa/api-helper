<?php

namespace Zahzah\ApiHelper\Facades;

use Illuminate\Support\Facades\Facade;
use Zahzah\ApiHelper\Contracts\ApiHelper as ContractsApiHelper;

/**
 * @see \Zahzah\ApiHelper\ApiHelper
 * @method static int expiration(?int $custom = null)
 * @method static void init() 
 */

class ApiHelper extends Facade
{
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor() { 
    return ContractsApiHelper::class;
  }
}