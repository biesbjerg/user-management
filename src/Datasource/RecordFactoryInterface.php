<?php
declare(strict_types=1);

namespace App\Datasource;

interface RecordFactoryInterface
{
    /**
     * Undocumented function
     *
     * @param array $row
     * @return RecordInterface
     */
    public function newRecord(array $row);

    /**
     * Undocumented function
     *
     * @param array[] $rows
     * @return RecordInterface[]
     */
    public function newRecordSet(array $rows): array;
}
