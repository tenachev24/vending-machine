<?php

declare(strict_types=1);

namespace App\Infrastructure\Abstracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionProperty;

abstract class DataTransferObject implements Jsonable, Arrayable
{
    protected array $_exceptKeys = [];
    protected array $_onlyKeys = [];
    protected ?string $_keyCaseConvert = null;

    /**
     * @param string $property
     * @return string
     */
    protected function propertyName(string $property): string
    {
        if (is_null($this->_keyCaseConvert)) {
            return $property;
        }

        return Str::of($property)->{$this->_keyCaseConvert}()->toString();
    }

    /**
     * @return array
     */
    public function all(): array
    {
        $data = [];
        $class = new ReflectionClass(static::class);
        $properties = $class->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $data[$this->propertyName($property->getName())] = $property->getValue($this);
        }

        return $data;
    }

    /**
     * @return array
     */
    public function filled(): array
    {
        return collect($this->toArray())->whereNotNull()->toArray();
    }

    /**
     * @return $this
     */
    public function reset(): static
    {
        $this->_keyCaseConvert = null;
        $this->_exceptKeys = [];
        $this->_onlyKeys = [];

        return clone $this;
    }

    /**
     * @return $this
     */
    public function toSnakeCase(): static
    {
        $this->_keyCaseConvert = 'snake';

        foreach ($this->_onlyKeys as $i => $originalKey) {
            $this->_onlyKeys[$i] = $this->propertyName($originalKey);
        }

        foreach ($this->_exceptKeys as $i => $originalKey) {
            $this->_exceptKeys[$i] = $this->propertyName($originalKey);
        }

        return clone $this;
    }

    /**
     * @param string ...$keys
     * @return $this
     */
    public function only(string ...$keys): static
    {
        $dataTransferObject = clone $this;

        foreach ($keys as $i => $originalKey) {
            $keys[$i] = $this->propertyName($originalKey);
        }

        $dataTransferObject->_onlyKeys = [...$this->_onlyKeys, ...$keys];

        return $dataTransferObject;
    }

    /**
     * @param string ...$keys
     * @return $this
     */
    public function except(string ...$keys): static
    {
        $dataTransferObject = clone $this;

        foreach ($keys as $i => $originalKey) {
            $keys[$i] = $this->propertyName($originalKey);
        }

        $dataTransferObject->_exceptKeys = [...$this->_exceptKeys, ...$keys];

        return $dataTransferObject;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        if (count($this->_onlyKeys)) {
            $array = Arr::only($this->all(), $this->_onlyKeys);
        } else {
            $array = Arr::except($this->all(), $this->_exceptKeys);
        }

        return $this->parseArray($array);
    }

    /**
     * @param array $array
     * @return array
     */
    protected function parseArray(array $array): array
    {
        foreach ($array as $key => $value) {
            if ($value instanceof self) {
                $array[$key] = $value->toArray();

                continue;
            }

            if (! is_array($value)) {
                continue;
            }

            $array[$key] = $this->parseArray($value);
        }

        return $array;
    }

    /**
     * @param $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}
