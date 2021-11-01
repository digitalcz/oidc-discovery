# OIDC Discovery

[![Latest Stable Version](http://poser.pugx.org/digitalcz/oidc-discovery/v)](https://packagist.org/packages/digitalcz/oidc-discovery) 
[![Total Downloads](http://poser.pugx.org/digitalcz/oidc-discovery/downloads)](https://packagist.org/packages/digitalcz/oidc-discovery) 
[![Latest Unstable Version](http://poser.pugx.org/digitalcz/oidc-discovery/v/unstable)](https://packagist.org/packages/digitalcz/oidc-discovery) 
[![License](http://poser.pugx.org/digitalcz/oidc-discovery/license)](https://packagist.org/packages/digitalcz/oidc-discovery) 
[![PHP Version Require](http://poser.pugx.org/digitalcz/oidc-discovery/require/php)](https://packagist.org/packages/digitalcz/oidc-discovery)
[![CI](https://github.com/digitalcz/oidc-discovery/workflows/CI/badge.svg)]()
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/digitalcz/oidc-discovery/badges/quality-score.png?b=0.x)](https://scrutinizer-ci.com/g/digitalcz/oidc-discovery/?branch=0.x)
[![codecov](https://codecov.io/gh/digitalcz/oidc-discovery/branch/0.x/graph/badge.svg?token=e8B81l7mzA)](https://codecov.io/gh/digitalcz/oidc-discovery)

PHP implementation of https://openid.net/specs/openid-connect-discovery-1_0.html

## Install

Via [Composer](https://getcomposer.org/)

```bash
$ composer require digitalcz/oidc-discovery
```

## Usage

If `php-http/discovery` package is present, DiscoveryFactory will use it to find PSR18 client and PSR17 factory.

```php
use DigitalCz\OpenIDConnect\Discovery\DiscovererFactory;

$discoverer = DiscovererFactory::create()
$providerMetadata = $discoverer->discover('https://accounts.google.com/.well-known/openid-configuration');
$issuer = $providerMetadata->issuer(); // https://accounts.google.com
$tokenEndpoint = $providerMetadata->tokenEndpoint(); // https://oauth2.googleapis.com/token
```

otherwise, you can provide these manually 
```php
use DigitalCz\OpenIDConnect\Discovery\DiscovererFactory;
use Http\Client\Curl\Client;
use Nyholm\Psr7\Factory\Psr17Factory;

$client = new Client();
$requestFactory = new Psr17Factory();
$discoverer = DiscovererFactory::create($client, $requestFactory);
```

### Add cache to avoid unnecessary calls
```php
use DigitalCz\OpenIDConnect\Discovery\DiscovererFactory;
use Symfony\Component\Cache\Adapter\FilesystemAdapter
use Symfony\Component\Cache\Psr16Cache;

$cache = new Psr16Cache(new FilesystemAdapter())
$discoverer = DiscovererFactory::create(cache: $cache, cacheTtl: 1800);
```

See [examples](examples) for more

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer csfix    # fix codestyle
$ composer checks   # run all checks 

# or separately
$ composer tests    # run phpunit
$ composer phpstan  # run phpstan
$ composer cs       # run codesniffer
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email devs@digital.cz instead of using the issue tracker.

## Credits

- [Digital Solutions s.r.o.][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[link-author]: https://github.com/digitalcz
[link-contributors]: ../../contributors
