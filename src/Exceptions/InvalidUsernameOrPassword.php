<?php

namespace Hanafalah\ApiHelper\Exceptions;

use Exception;
use Hanafalah\LaravelSupport\Concerns\Support\HasResponse;

class InvalidUsernameOrPassword extends Exception
{
  use HasResponse;

  /**
   * Render a JSON response for unauthorized access.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function render()
  {
    return $this->sendResponse(null, 422, 'Incorrect password or username.');
  }
}
