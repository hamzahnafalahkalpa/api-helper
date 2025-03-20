<?php

namespace Hanafalah\ApiHelper\Exceptions;

use Exception;

class MissingModelCodeException extends Exception
{
  /**
   * Render the response as a JSON object with a 401 status code
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function render()
  {
    return response()->json([
      'response' => null,
      'metadata' => [
        'message' => 'Missing "model" code.',
        'code' => 401
      ]
    ], 401);
  }
}
