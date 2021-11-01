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
    public function __construct(
        private ?ClientInterface $client = null,
        private ?RequestFactoryInterface $requestFactory = null,
        private ?CacheInterface $cache = null,
        private int $cacheTtl = CachedDiscoverer::DEFAULT_TTL
    ) {
    }

    public function create(): Discoverer
    {
        $discoverer = new HttpDiscoverer(
            $this->client ?? $this->discoverClient(),
            $this->requestFactory ?? $this->discoverRequestFactory()
        );

        if ($this->cache !== null) {
            $discoverer = new CachedDiscoverer($discoverer, $this->cache, $this->cacheTtl);
        }

        return $discoverer;
    }

    private function discoverClient(): ClientInterface
    {
        if (class_exists(Psr18ClientDiscovery::class)) {
            return Psr18ClientDiscovery::find();
        }

        throw new LogicException('Unable to discover ' . ClientInterface::class);
    }

    private function discoverRequestFactory(): RequestFactoryInterface
    {
        if (class_exists(Psr17FactoryDiscovery::class)) {
            return Psr17FactoryDiscovery::findRequestFactory();
        }

        throw new LogicException('Unable to discover ' . RequestFactoryInterface::class);
    }
}
