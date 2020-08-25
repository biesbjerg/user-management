<?php
declare(strict_types=1);

namespace App\Datasource;

interface RecordInterface
{
    public function setData(array $data): void;

    public function getData(): array;
}
