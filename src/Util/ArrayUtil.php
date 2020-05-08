<?php

namespace Faibl\ElasticsearchBundle\Util;

class ArrayUtil
{
    public static function removeEmptyKeys(array $data = []): array
    {
        $data = array_map(function ($value) {
            return is_array($value) ? ArrayUtil::removeEmptyKeys($value) : $value;
        }, $data);

        return array_filter($data, function ($value) {
            return !is_null($value);
        });
    }

    public static function filterEmpty(?array $data = []): array
    {
        return $data ? array_values(self::removeEmptyKeys($data)) : [];
    }
}
