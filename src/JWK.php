<?php

declare(strict_types=1);

namespace DigitalCz\OpenIDConnect\Discovery;

use DigitalCz\OpenIDConnect\Discovery\Traits\ParametersTrait;
use InvalidArgumentException;

final class JWK
{
    use ParametersTrait;

    /** @param array<string, mixed> $values */
    public function __construct(array $values)
    {
        $values['kty'] ?? throw new InvalidArgumentException('Key "kty" is mandatory');
        $this->parameters = $values;
    }
}
