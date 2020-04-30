<?php

namespace EliPett\Transformation\Services;

use EliPett\Transformation\Contracts\Transformer;

class TransformerFactory
{
    public static function make($item , string $class): Transformer
    {
        $transformer = new $class($item);

        if (!$transformer instanceof Transformer) {
            throw new \InvalidArgumentException("Class '$class' does not implement EliPett\Transformation\Contracts\Transformer");
        }

        return $transformer;
    }
}
