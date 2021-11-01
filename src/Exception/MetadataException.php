<?php

declare(strict_types=1);

namespace DigitalCz\OpenIDConnect\Discovery\Exception;

use RuntimeException;

class MetadataException extends RuntimeException
{
    public static function missing(string $key): self
    {
        return new self("Missing metadata value \"$key\"");
    }
}
