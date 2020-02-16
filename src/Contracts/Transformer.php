<?php

namespace EliPett\Transformation\Contracts;

interface Transformer
{
    public function __construct($item);
    public function transform();
}
