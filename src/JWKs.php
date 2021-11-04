<?php

declare(strict_types=1);

namespace DigitalCz\OpenIDConnect\Discovery;

use InvalidArgumentException;
use JsonSerializable;

class JWKs implements JsonSerializable
{
    /** @var JWK[] */
    private array $keys;

    /** @param JWK[] $keys */
    public function __construct(array $keys)
    {
        $this->keys = $keys;
    }

    /** @param array<string, mixed> $values */
    public static function fromArray(array $values): self
    {
        $keys = $values['keys'] ?? throw new InvalidArgumentException('Key "keys" is missing');

        return new self(array_map(static fn (array $keyData) => new JWK($keyData), $keys));
    }

    /** @return JWK[] */
    public function keys(): array
    {
        return $this->keys;
    }

    /** @return array{keys: array<int, array<string, string>>} */
    public function toArray(): array
    {
        return ['keys' => array_map(static fn (JWK $key) => $key->all(), $this->keys())];
    }

    /** @return array{keys: array<int, array<string, string>>} */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
