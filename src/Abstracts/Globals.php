<?php
declare(strict_types=1);

namespace Coine\WpHelper\Abstracts;

use Coine\WpHelper\Traits\Singleton;

abstract class Globals
{
    private mixed $temporaryKeyValue = null;
    private array $parameters;
    use Singleton;

    public function notHas(string ...$key): bool
    {
        return !self::has(...$key);
    }

    public function has(string ...$key): bool
    {
        foreach ($key as $k) {
            if (!isset($this->getData()[$k]) || empty($this->getData()[$k])) {
                return false;
            }
        }

        return true;
    }

    abstract protected function getData(): array;

    public function all(): array
    {
        return $this->getData();
    }

    public function key(string $key): self
    {
        $this->temporaryKeyValue = $this->get($key);

        return $this;
    }

    public function get(string $key = '', mixed $default = null): mixed
    {
        return $this->has($key) ? $this->getData()[$key] : $default;

    }

    public function isDifferent(...$value): bool
    {
        return $this->cleanAndReturn(!in_array($this->temporaryKeyValue, $value));
    }

    protected function cleanAndReturn(bool $value): bool
    {
        $this->temporaryKeyValue = null;

        return $value;
    }

    public function isOneOf(...$value): bool
    {
        return $this->cleanAndReturn(in_array($this->temporaryKeyValue, $value));
    }

    public function getInt(string $key = '', ?int $default = null): ?int
    {
        return $this->has($key) ? (int)$this->getData()[$key] : $default;
    }

    public function getBool(string $key = '', ?bool $default = null): ?bool
    {
        return $this->has($key) ? filter_var($this->getData()[$key], FILTER_VALIDATE_BOOL) : $default;
    }

    public function withError(array ...$error): self
    {
        $this->parameters = $error;

        return $this;
    }

    public function notHasOneOf(string ...$keys): bool
    {
        return !$this->hasOneOf(...$keys);
    }

    public function hasOneOf(string ...$keys): bool
    {
        foreach ($keys as $key) {
            if ($this->has($key)) {
                return true;
            }
        }

        return false;
    }

    public function getAndFallbackTo(string $key, mixed ...$values)
    {
        if ($this->has($key)) {
            return $this->get($key);
        }
        foreach ($values as $value) {
            if (!empty($value)) {
                return $value;
            }
        }

        return '';
    }

    protected function except(array $data, string ...$key): array
    {
        foreach ($key as $k) {
            unset($data[$k]);
        }

        return $data;
    }

}