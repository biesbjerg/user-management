<?php
declare(strict_types=1);

namespace App\Datasource;

interface RecordInterface
{
    /**
     * Undocumented function
     *
     * @param array $data
     * @return void
     */
    public function setData(array $data): void;

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getData(): array;
}
