<?php

declare(strict_types=1);

namespace DigitalCz\OpenIDConnect\Discovery;

use DigitalCz\OpenIDConnect\Discovery\Exception\DiscoveryException;
use Http\Client\Exception\NetworkException;
use Http\Mock\Client;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Request;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DigitalCz\OpenIDConnect\Discovery\HttpDiscoverer
 */
class HttpDiscovererTest extends TestCase
{
    public function testDiscover(): void
    {
        $configuration = file_get_contents(TESTS_DIR . '/configuration.json');

        self::assertIsString($configuration);

        $client = new Client();
        $client->addResponse(new Response(200, [], $configuration));
        $discoverer = new HttpDiscoverer($client, new Psr17Factory());
        $metadata = $discoverer->discover('https://example.com/.well-known/openid-configuration');

        $lastRequest = $client->getLastRequest();
        self::assertSame('GET', $lastRequest->getMethod());
        self::assertSame('https://example.com/.well-known/openid-configuration', (string)$lastRequest->getUri());
        self::assertSame('https://accounts.google.com', $metadata->issuer());
        self::assertSame('https://accounts.google.com/o/oauth2/v2/auth', $metadata->authorizationEndpoint());
    }

    public function testClientException(): void
    {
        $request = new Request('GET', 'https://example.com/.well-known/openid-configuration');

        $client = new Client();
        $client->addException(new NetworkException('network error', $request));

        $this->expectException(DiscoveryException::class);
        $this->expectErrorMessage('network error');

        $discoverer = new HttpDiscoverer($client, new Psr17Factory());
        $discoverer->discover('https://example.com/.well-known/openid-configuration');
    }

    public function testBadResponseStatusCode(): void
    {
        $client = new Client();
        $client->addResponse(new Response(400, [], 'Bad request'));
        $discoverer = new HttpDiscoverer($client, new Psr17Factory());

        $this->expectException(DiscoveryException::class);
        $this->expectExceptionMessage('Bad response status code 400');

        $discoverer->discover('https://example.com/.well-known/openid-configuration');
    }

    public function testUnableToParseResponse(): void
    {
        $client = new Client();
        $client->addResponse(new Response(200, [], '{this is not a json"'));
        $discoverer = new HttpDiscoverer($client, new Psr17Factory());

        $this->expectException(DiscoveryException::class);
        $this->expectExceptionMessage('Unable to parse response');

        $discoverer->discover('https://example.com/.well-known/openid-configuration');
    }
}
