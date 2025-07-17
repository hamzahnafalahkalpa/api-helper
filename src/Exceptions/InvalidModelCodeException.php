<?php

namespace Hanafalah\ApiHelper\Exceptions;

use Exception;

class InvalidModelCodeException extends Exception
{
  /**
   * Render a JSON response for an invalid "faskes" code.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function render()
  {
    return response()->json([
      'response' => null,
      'metadata' => [
        'message' => 'Invalid "model" code.',
        'code' => 401
      ]
    ], 401);
  }
}
