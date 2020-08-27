<?php
declare(strict_types=1);

namespace App\Exception;

use App\Validation\ValidatorInterface;
use DomainException;
use Throwable;

final class ValidationException extends DomainException
{
    private ValidatorInterface $validator;

    public function __construct(
        string $message,
        ValidatorInterface $validator,
        int $code = 422,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->validator = $validator;
    }

    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }
}
