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
        $configuration = file_get_contents(TESTS_DIR . '/Dummy/configuration.json');
        $jwks = file_get_contents(TESTS_DIR . '/Dummy/jwks.json');

        self::assertIsString($configuration);
        self::assertIsString($jwks);

        $client = new Client();
        $client->addResponse(new Response(200, [], $configuration));
        $client->addResponse(new Response(200, [], $jwks));
        $discoverer = new HttpDiscoverer($client, new Psr17Factory());
        $metadata = $discoverer->discover('https://example.com/.well-known/openid-configuration');

        self::assertCount(2, $client->getRequests());

        $configurationRequest = $client->getRequests()[0];
        self::assertSame('GET', $configurationRequest->getMethod());
        self::assertSame(
            'https://example.com/.well-known/openid-configuration',
            (string)$configurationRequest->getUri()
        );

        $jwksRequest = $client->getRequests()[1];
        self::assertSame('GET', $jwksRequest->getMethod());
        self::assertSame('https://www.googleapis.com/oauth2/v3/certs', (string)$jwksRequest->getUri());

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
