<?php

declare(strict_types=1);

namespace DigitalCz\OpenIDConnect\Discovery;

use DigitalCz\OpenIDConnect\Discovery\Traits\ParametersTrait;
use JsonSerializable;

final class ProviderMetadata implements JsonSerializable
{
    use ParametersTrait;

    private JWKs $JWKs;

    /** @param array<string, mixed> $metadata */
    public function __construct(array $metadata, JWKs $JWKs)
    {
        $this->parameters = $metadata;
        $this->JWKs = $JWKs;
    }

    public function issuer(): string
    {
        return $this->ensure('issuer');
    }

    public function authorizationEndpoint(): string
    {
        return $this->ensure('authorization_endpoint');
    }

    public function tokenEndpoint(): ?string
    {
        return $this->get('token_endpoint');
    }

    public function userinfoEndpoint(): ?string
    {
        return $this->get('userinfo_endpoint');
    }

    public function jwksUri(): string
    {
        return $this->ensure('jwks_uri');
    }

    public function registrationEndpoint(): ?string
    {
        return $this->get('registration_endpoint');
    }

    /** @return string[] */
    public function scopesSupported(): array
    {
        return $this->get('scopes_supported', []);
    }

    /** @return string[] */
    public function responseTypesSupported(): array
    {
        return $this->ensure('response_types_supported');
    }

    /** @return string[] */
    public function responseModesSupported(): array
    {
        return $this->get('response_modes_supported', ['query', 'fragment']);
    }

    /** @return string[] */
    public function grantTypesSupported(): array
    {
        return $this->get('grant_types_supported', ['authorization_code', 'implicit']);
    }

    /** @return string[] */
    public function acrValuesSupported(): array
    {
        return $this->get('acr_values_supported', []);
    }

    /** @return string[] */
    public function subjectTypesSupported(): array
    {
        return $this->ensure('subject_types_supported');
    }

    /** @return string[] */
    public function idTokenSigningAlgValuesSupported(): array
    {
        return $this->ensure('id_token_signing_alg_values_supported');
    }

    /** @return string[] */
    public function idTokenEncryptionAlgValuesSupported(): array
    {
        return $this->get('id_token_encryption_alg_values_supported', []);
    }

    /** @return string[] */
    public function idTokenEncryptionEncValuesSupported(): array
    {
        return $this->get('id_token_encryption_enc_values_supported', []);
    }

    /** @return string[] */
    public function userinfoSigningAlgValuesSupported(): array
    {
        return $this->get('userinfo_signing_alg_values_supported', []);
    }

    /** @return string[] */
    public function userinfoEncryptionAlgValuesSupported(): array
    {
        return $this->get('userinfo_encryption_alg_values_supported', []);
    }

    /** @return string[] */
    public function userinfoEncryptionEncValuesSupported(): array
    {
        return $this->get('userinfo_encryption_enc_values_supported', []);
    }

    /** @return string[] */
    public function requestObjectSigningAlgValuesSupported(): array
    {
        return $this->get('request_object_signing_alg_values_supported', []);
    }

    /** @return string[] */
    public function requestObjectEncryptionAlgValuesSupported(): array
    {
        return $this->get('request_object_encryption_alg_values_supported', []);
    }

    /** @return string[] */
    public function requestObjectEncryptionEncValuesSupported(): array
    {
        return $this->get('request_object_encryption_enc_values_supported', []);
    }

    /** @return string[] */
    public function tokenEndpointAuthMethodsSupported(): array
    {
        return $this->get('token_endpoint_auth_methods_supported', ['client_secret_basic']);
    }

    /** @return string[] */
    public function tokenEndpointAuthSigningAlgValuesSupported(): array
    {
        return $this->get('token_endpoint_auth_signing_alg_values_supported', []);
    }

    /** @return string[] */
    public function displayValuesSupported(): array
    {
        return $this->get('display_values_supported', []);
    }

    /** @return string[] */
    public function claimTypesSupported(): array
    {
        return $this->get('claim_types_supported', []);
    }

    /** @return string[] */
    public function claimsSupported(): array
    {
        return $this->get('claims_supported', []);
    }

    public function serviceDocumentation(): string
    {
        return $this->get('service_documentation');
    }

    /** @return string[] */
    public function claimsLocalesSupported(): array
    {
        return $this->get('claims_locales_supported', []);
    }

    /** @return string[] */
    public function uiLocalesSupported(): array
    {
        return $this->get('ui_locales_supported', []);
    }

    public function claimsParameterSupported(): bool
    {
        return $this->get('claims_parameter_supported', false);
    }

    public function requestParameterSupported(): bool
    {
        return $this->get('request_parameter_supported', false);
    }

    public function requestUriParameterSupported(): bool
    {
        return $this->get('request_uri_parameter_supported', false);
    }

    public function requireRequestUriRegistration(): bool
    {
        return $this->get('require_request_uri_registration', false);
    }

    public function opPolicyUri(): string
    {
        return $this->get('op_policy_uri');
    }

    public function opTosUri(): string
    {
        return $this->get('op_tos_uri');
    }

    public function getJWKs(): JWKs
    {
        return $this->JWKs;
    }
}
