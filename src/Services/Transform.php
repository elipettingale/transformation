<?php

namespace EliPett\Transformation\Services;

class Transform
{
    public static function all($items, string $transformerClass)
    {
        $transformer = TransformerFactory::make($items[0], $transformerClass);

        $data = [];

        foreach($items as $item) {
            $data[] = $transformer->transform($item);
        }

        return $data;
    }

    public static function one($item, string $transformerClass)
    {
        $transformer = TransformerFactory::make($item, $transformerClass);

        return $transformer->transform($item);
    }
}
