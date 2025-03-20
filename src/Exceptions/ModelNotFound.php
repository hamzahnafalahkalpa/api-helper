<?php

namespace Hanafalah\ApiHelper\Exceptions;

use Exception;

class ModelNotFound extends Exception
{
  /**
   * Render the exception handling callbacks for the application.
   *
   * @return void
   */
  public function render()
  {
    return response()->json([
      'response' => null,
      'metadata' => [
        'message' => 'Data not found.',
        'code' => 404
      ]
    ], 404);
  }
}
