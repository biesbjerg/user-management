<?php
declare(strict_types=1);

namespace App\Datasource;

use App\Datasource\RecordInterface;

abstract class AbstractRecord implements RecordInterface
{
    public function __construct(array $data = [])
    {
        $this->setData($data);
    }

    public function setData(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function getData(): array
    {
        return get_object_vars($this);
    }
}
