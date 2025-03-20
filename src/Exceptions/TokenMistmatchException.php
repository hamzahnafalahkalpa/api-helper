<?php

namespace Zahzah\ApiHelper\Exceptions;

use Exception;

class TokenMistmatchException extends Exception
{
    /**
   * Register the exception handling callbacks for the application.
   *
   * @return void
   */
    public function render()
    {
      return response()->json([
        'response' => null,
        'metadata' => [
          'message' => 'Invalid token.',
          'code' => 498
        ]
      ], 498);
    }
}
