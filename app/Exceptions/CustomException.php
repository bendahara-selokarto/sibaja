<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    public function __construct(string $message, int $code = 0, \Throwable $previous = null){
        parent::__construct($message, $code, $previous);
        
    }
    public function render(){
        return response()->json(
            ['error' => $this->message], 500);
    }
}
