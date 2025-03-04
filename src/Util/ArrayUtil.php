<?php

namespace Faibl\ElasticsearchBundle\Util;

class ArrayUtil
{
    public static function filterEmpty(?array $data = []): array
    {
        return array_filter($data, fn($item) => !empty($item));
    }

    public static function filterNull(?array $data = []): array
    {
        return array_filter($data, fn($item) => !is_null($item));
    }
}
