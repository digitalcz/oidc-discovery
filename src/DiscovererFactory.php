<?php

declare(strict_types=1);

namespace DigitalCz\OpenIDConnect\Discovery;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use LogicException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\SimpleCache\CacheInterface;

final class DiscovererFactory
{
    public static function create(
        ?ClientInterface $client = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?CacheInterface $cache = null,
        int $cacheTtl = CachedDiscoverer::DEFAULT_TTL
    ): Discoverer {
        $client ??= self::discoverClient();
        $requestFactory ??= self::discoverRequestFactory();

        $discoverer = new HttpDiscoverer($client, $requestFactory);

        if ($cache !== null) {
            $discoverer = new CachedDiscoverer($discoverer, $cache, $cacheTtl);
        }

        return $discoverer;
    }

    private static function discoverClient(): ClientInterface
    {
        if (class_exists(Psr18ClientDiscovery::class)) {
            return Psr18ClientDiscovery::find();
        }

        throw new LogicException('Unable to discover ' . ClientInterface::class);
    }

    private static function discoverRequestFactory(): RequestFactoryInterface
    {
        if (class_exists(Psr17FactoryDiscovery::class)) {
            return Psr17FactoryDiscovery::findRequestFactory();
        }

        throw new LogicException('Unable to discover ' . RequestFactoryInterface::class);
    }
}
