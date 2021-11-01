<?php

declare(strict_types=1);

namespace DigitalCz\OpenIDConnect\Discovery;

use DigitalCz\OpenIDConnect\Discovery\Exception\DiscoveryException;

interface Discoverer
{
    /**
     * @throws DiscoveryException
     */
    public function discover(string $uri): ProviderMetadata;
}
