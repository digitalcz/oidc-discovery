<?php

declare(strict_types=1);

namespace DigitalCz\OpenIDConnect\Discovery;

use Http\Client\Curl\Client;
use Http\Mock\Client as MockClient;
use Nyholm\NSA;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\NullAdapter;
use Symfony\Component\Cache\Psr16Cache;

/**
 * @covers \DigitalCz\OpenIDConnect\Discovery\DiscovererFactory
 */
class DiscovererFactoryTest extends TestCase
{
    public function testCreateWithDiscovery(): void
    {
        $discoverer = DiscovererFactory::create();

        self::assertInstanceOf(Client::class, NSA::getProperty($discoverer, 'client'));
        self::assertInstanceOf(Psr17Factory::class, NSA::getProperty($discoverer, 'requestFactory'));
    }

    public function testCreateWithProvided(): void
    {
        $client = new MockClient();
        $requestFactory = new Psr17Factory();
        $cache = new Psr16Cache(new NullAdapter());

        $cachedDiscoverer = DiscovererFactory::create($client, $requestFactory, $cache);

        self::assertInstanceOf(CachedDiscoverer::class, $cachedDiscoverer);
        self::assertSame($cache, NSA::getProperty($cachedDiscoverer, 'cache'));
        $discoverer = NSA::getProperty($cachedDiscoverer, 'inner');

        self::assertSame($client, NSA::getProperty($discoverer, 'client'));
        self::assertSame($requestFactory, NSA::getProperty($discoverer, 'requestFactory'));
    }
}
