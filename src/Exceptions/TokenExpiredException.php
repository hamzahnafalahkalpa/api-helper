<?php

namespace Hanafalah\ApiHelper\Exceptions;

use Exception;
use Illuminate\Http\Response;

class TokenExpiredException extends Exception
{
  /**
   * Renders a JSON response indicating that the token has expired.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function render()
  {
    return response()->json([
      'response' => null,
      'metadata' => [
        'message' => 'Token expired.',
        'code' => Response::HTTP_CREATED
      ]
    ], Response::HTTP_CREATED);
  }
}
