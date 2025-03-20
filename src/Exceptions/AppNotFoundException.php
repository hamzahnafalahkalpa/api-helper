<?php

namespace Hanafalah\ApiHelper\Exceptions;

use Exception;
use Hanafalah\LaravelSupport\Concerns\Support\HasResponse;

class AppNotFoundException extends Exception
{
  use HasResponse;

  /**
   * Render a JSON response for unauthorized access.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function render()
  {
    return $this->sendResponse(null, 401, 'App code not found.');
  }
}
