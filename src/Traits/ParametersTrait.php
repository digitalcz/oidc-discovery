<?php

declare(strict_types=1);

namespace DigitalCz\OpenIDConnect\Discovery\Traits;

use RuntimeException;

trait ParametersTrait
{
    /** @var array<string, mixed> */
    private array $parameters = [];

    public function has(string $key): bool
    {
        return isset($this->parameters[$key]);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->parameters[$key] ?? $default;
    }

    public function ensure(string $key): mixed
    {
        return $this->get($key) ?? throw new RuntimeException("Missing key \"$key\"");
    }

    /** @return array<string, mixed> */
    public function all(): array
    {
        return $this->parameters;
    }

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return $this->all();
    }
}
