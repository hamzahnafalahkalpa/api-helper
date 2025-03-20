<?php

namespace Zahzah\ApiHelper\Exceptions;

use Exception;

class InvalidTimestamp extends Exception
{
/**
 * Renders the function to return a JSON response with an error message and code.
 *
 * @return \Illuminate\Http\JsonResponse
 */
public function render(){
  // Return a JSON response with an error message and code
  return response()->json([
    'response' => null,
    'metadata' => [
      'message' => 'Invalid Timestamp.',
      'code' => 401
    ]
  ], 401);
}
}
