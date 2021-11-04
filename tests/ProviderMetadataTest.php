<?php

declare(strict_types=1);

namespace DigitalCz\OpenIDConnect\Discovery;

use PHPUnit\Framework\TestCase;

/**
 * @covers \DigitalCz\OpenIDConnect\Discovery\ProviderMetadata
 * @covers \DigitalCz\OpenIDConnect\Discovery\Traits\ParametersTrait
 */
class ProviderMetadataTest extends TestCase
{
    public function testMethods(): void
    {
        $config = (string)file_get_contents(TESTS_DIR . '/Dummy/configuration.json');
        $values = json_decode($config, true, 512, JSON_THROW_ON_ERROR);
        $metadata = new ProviderMetadata($values, new JWKs([]));

        self::assertSame('https://accounts.google.com', $metadata->issuer());
        self::assertSame('https://accounts.google.com/o/oauth2/v2/auth', $metadata->authorizationEndpoint());
        self::assertSame('https://oauth2.googleapis.com/token', $metadata->tokenEndpoint());
        self::assertSame('https://openidconnect.googleapis.com/v1/userinfo', $metadata->userinfoEndpoint());
        self::assertSame(
            ['code', 'token', 'id_token', 'code token', 'code id_token', 'token id_token', 'code token id_token', 'none',],
            $metadata->responseTypesSupported()
        );
        self::assertSame(['public'], $metadata->subjectTypesSupported());
        self::assertSame(['RS256'], $metadata->idTokenSigningAlgValuesSupported());
        self::assertSame(['openid', 'email', 'profile'], $metadata->scopesSupported());
        self::assertSame(['client_secret_post', 'client_secret_basic'], $metadata->tokenEndpointAuthMethodsSupported());
        self::assertSame(
            ['aud', 'email', 'email_verified', 'exp', 'family_name', 'given_name', 'iat', 'iss', 'locale', 'name', 'picture', 'sub',],
            $metadata->claimsSupported()
        );
        self::assertSame(
            ['authorization_code', 'refresh_token', 'urn:ietf:params:oauth:grant-type:device_code', 'urn:ietf:params:oauth:grant-type:jwt-bearer',],
            $metadata->grantTypesSupported()
        );
        self::assertTrue($metadata->has('device_authorization_endpoint'));
        self::assertFalse($metadata->has('lorem_ipsum'));
        self::assertSame('https://oauth2.googleapis.com/device/code', $metadata->get('device_authorization_endpoint'));
        self::assertSame($values, $metadata->all());
        self::assertSame($values, $metadata->jsonSerialize());
    }
}
