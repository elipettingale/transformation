<?php

namespace EliPett\Transformation\Services;

class Transform
{
    public static function all($items, string $transformerClass)
    {
        $data = [];

        foreach($items as $item) {
            $data[] = self::one($item, $transformerClass);
        }

        return $data;
    }

    public static function one($item, string $transformerClass)
    {
        $transformer = TransformerFactory::make($item, $transformerClass);

        return $transformer->transform($item);
    }
}
