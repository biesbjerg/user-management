<?php
declare(strict_types=1);

namespace App\Validation;

interface ValidatorInterface
{
    public function check(array $data, string $rules = 'default'): bool;

    public function hasErrors(): bool;

    public function getErrors(): array;

    public function hasError(string $key): bool;

    public function getError(string $key): ?string;
}
