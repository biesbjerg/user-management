<?php
declare(strict_types=1);

namespace App\Validation;

use App\Datasource\RecordInterface;
use App\Validation\ValidatorInterface;
use BadMethodCallException;

abstract class AbstractValidator implements ValidatorInterface
{
    protected array $messages = [];

    public function check(RecordInterface $data, string $validate = 'default'): bool
    {
        $this->messages = [];

        $method = 'validate' . ucfirst($validate);
        if (!method_exists($this, $method)) {
            throw new BadMethodCallException(sprintf('Validation method not found: %s', $method));
        }
        $this->{$method}($data);

        return !$this->hasErrors();
    }

    public function hasErrors(): bool
    {
        return count($this->messages) > 0;
    }

    public function getErrors(): array
    {
        return $this->messages;
    }

    public function hasError(string $key): bool
    {
        return array_key_exists($key, $this->messages);
    }

    public function getError(string $key): ?string
    {
        return $this->messages[$key] ?? null;
    }
}
