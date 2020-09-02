<?php
declare(strict_types=1);

namespace App\Datasource\User;

use App\Datasource\RecordFactoryInterface;

class UserRecordFactory implements RecordFactoryInterface
{
    /**
     * Undocumented function
     *
     * @param array $row
     * @return UserRecord
     */
    public function newRecord(array $row): UserRecord
    {
        return new UserRecord($row);
    }

    /**
     * Undocumented function
     *
     * @param array[] $rows
     * @return UserRecord[]
     */
    public function newRecordSet(array $rows): array
    {
        $collection = [];
        foreach ($rows as $row) {
            $collection[] = $this->newRecord($row);
        }
        return $collection;
    }
}
