<?php

declare(strict_types=1);

namespace DigitalCz\OpenIDConnect\Discovery;

use DigitalCz\OpenIDConnect\Discovery\Exception\MetadataException;

trait MetadataTrait
{
    /** @var array<string, mixed> */
    private array $metadata = [];

    public function has(string $key): bool
    {
        return isset($this->metadata[$key]);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->metadata[$key] ?? $default;
    }

    public function ensure(string $key): mixed
    {
        return $this->get($key) ?? MetadataException::missing($key);
    }

    /** @return array<string, mixed> */
    public function all(): array
    {
        return $this->metadata;
    }

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return $this->all();
    }
}
