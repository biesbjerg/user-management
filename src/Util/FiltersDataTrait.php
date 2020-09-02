<?php
declare(strict_types=1);

namespace App\Util;

trait FiltersDataTrait
{
    protected function filterData(array $data, array $fields): array
    {
        return array_intersect_key($data, array_flip($fields));
    }
}
