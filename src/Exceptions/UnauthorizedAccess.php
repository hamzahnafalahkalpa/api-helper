<?php

namespace Zahzah\ApiHelper\Exceptions;

use Exception;
use Zahzah\LaravelSupport\Concerns\Support\HasResponse;

class UnauthorizedAccess extends Exception
{
  use HasResponse;

  /**
   * Render a JSON response for unauthorized access.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function render(){
    return $this->sendResponse(null, 401, 'Unauthorized Access.');
  }
}
