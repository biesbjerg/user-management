<?php
declare(strict_types=1);

namespace App\Validation;

use App\Datasource\RecordInterface;

interface ValidatorInterface
{
    public function check(RecordInterface $record, string $rules = 'default'): bool;

    public function hasErrors(): bool;

    public function getErrors(): array;

    public function hasError(string $key): bool;

    public function getError(string $key): ?string;
}
