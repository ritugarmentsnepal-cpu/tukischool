<?php

namespace App\Exceptions;

use Exception;

class InsufficientCreditsException extends Exception
{
    public int $required;
    public int $available;

    public function __construct(string $message, int $required, int $available)
    {
        parent::__construct($message, 402);
        $this->required = $required;
        $this->available = $available;
    }

    public function render($request)
    {
        return response()->json([
            'error' => 'insufficient_credits',
            'message' => $this->getMessage(),
            'required' => $this->required,
            'available' => $this->available,
        ], 402);
    }
}
