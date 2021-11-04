<?php

declare(strict_types=1);

namespace DigitalCz\OpenIDConnect\Discovery;

use DigitalCz\OpenIDConnect\Discovery\Exception\DiscoveryException;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

final class HttpDiscoverer implements Discoverer
{
    public function __construct(private ClientInterface $client, private RequestFactoryInterface $requestFactory)
    {
    }

    /**
     * @throws DiscoveryException
     */
    public function discover(string $uri): ProviderMetadata
    {
        $configuration = $this->sendRequest($uri);
        $jwks = $this->sendRequest($configuration['jwks_uri']);

        return new ProviderMetadata($configuration, JWKs::fromArray($jwks));
    }

    /**
     * @return array<string, mixed>
     */
    private function sendRequest(string $uri): array
    {
        $request = $this->requestFactory->createRequest('GET', $uri);

        try {
            $response = $this->client->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new DiscoveryException($e->getMessage(), $e->getCode(), $e);
        }

        $statusCode = $response->getStatusCode();

        if ($statusCode !== 200) {
            throw new DiscoveryException('Bad response status code ' . $response->getStatusCode() . ' on ' . $uri);
        }

        try {
            return json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new DiscoveryException('Unable to parse response from ' . $uri, $e->getCode(), $e);
        }
    }
}
