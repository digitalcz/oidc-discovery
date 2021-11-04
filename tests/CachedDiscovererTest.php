<?php

declare(strict_types=1);

namespace DigitalCz\OpenIDConnect\Discovery;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;

/**
 * @covers \DigitalCz\OpenIDConnect\Discovery\CachedDiscoverer
 */
class CachedDiscovererTest extends TestCase
{
    public function testDiscover(): void
    {
        $innerDiscoverer = $this->createMock(Discoverer::class);
        $innerDiscoverer->expects(self::once())
            ->method('discover')
            ->with(self::equalTo('https://example.com/.well-known/openid-configuration'))
            ->willReturn(new ProviderMetadata(['issuer' => 'https://example.com'], new JWKs([])));

        $cache = new Psr16Cache(new ArrayAdapter());
        $cachedDiscoverer = new CachedDiscoverer($innerDiscoverer, $cache);

        // calling multiple times, request is made only once, otherwise the mock fails
        $metadata = $cachedDiscoverer->discover('https://example.com/.well-known/openid-configuration');
        self::assertSame('https://example.com', $metadata->issuer());

        $metadata = $cachedDiscoverer->discover('https://example.com/.well-known/openid-configuration');
        self::assertSame('https://example.com', $metadata->issuer());

        $metadata = $cachedDiscoverer->discover('https://example.com/.well-known/openid-configuration');
        self::assertSame('https://example.com', $metadata->issuer());
    }
}
