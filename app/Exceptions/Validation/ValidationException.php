<?php

namespace App\Exceptions\Validation;

use Illuminate\Support\MessageBag;
use Throwable;

class ValidationException extends \Exception
{
    protected $validationErrors;

    public function __construct($message = "", $code = 0, MessageBag $errors = null, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->validationErrors = $errors;
    }

    /**
     * @return MessageBag|array
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    /**
     * @param array $validationErrors
     */
    public function setValidationErrors(MessageBag $validationErrors): void
    {
        $this->validationErrors = $validationErrors;
    }
}
