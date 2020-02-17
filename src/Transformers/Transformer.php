<?php

namespace EliPett\Transformation\Transformers;

use EliPett\Transformation\Contracts\Transformer as TransformerContract;

class Transformer implements TransformerContract
{
    protected $item = null;

    protected $includes = [];
    protected $excludes = [];
    protected $rename = [];

    public function __construct($item)
    {
        $this->item = $item;

        if ($this->includes !== [] && $this->excludes !== []) {
            throw new \InvalidArgumentException("Invalid transformer setup, please use includes or excludes, not both.");
        }
    }

    public function transform()
    {
        if ($this->includes !== []) {
            return $this->transformUsingIncludes();
        }

        return $this->transformUsingExcludes();
    }

    private function transformUsingIncludes()
    {
        $data = [];

        foreach($this->includes as $attribute) {
            $this->transformAttribute($data, $attribute);
        }

        return $data;
    }

    private function transformUsingExcludes()
    {
        $data = [];

        foreach($this->attributes() as $attribute) {
            if (!in_array($attribute, $this->excludes)) {
                $this->transformAttribute($data, $attribute);
            }
        }

        return $data;
    }

    protected function attributes()
    {
        $attributes = [];

        foreach($this->item as $attribute => $value) {
            $attributes[] = $attribute;
        }

        $methods = preg_grep('/get(.*)Attribute/', get_class_methods($this));

        foreach($methods as $method) {
            $attribute = substr($method, 3, strlen($method) - 12);
            $attributes[] = lower_snake_case($attribute);
        }

        return $attributes;
    }

    private function transformAttribute(&$data, $attribute)
    {
        $nickname = $attribute;

        if (isset($this->rename[$attribute])) {
            $nickname = $this->rename[$attribute];
        }

        $data[$nickname] = $this->getValue($attribute);
    }

    protected function getValue($attribute)
    {
        $method = 'get' . lower_camel_case($attribute) . 'Attribute';

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        return $this->item[$attribute];
    }
}
