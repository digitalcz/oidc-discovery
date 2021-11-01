<?php

declare(strict_types=1);

use DigitalCz\OpenIDConnect\Discovery\CachedDiscoverer;
use DigitalCz\OpenIDConnect\Discovery\HttpDiscoverer;
use Http\Client\Curl\Client;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

require dirname(__DIR__) . '/vendor/autoload.php';

$client = new Client();
$requestFactory = new Psr17Factory();
$cache = new Psr16Cache(new FilesystemAdapter());
$httpDiscoverer = new HttpDiscoverer($client, $requestFactory);
$cachedDiscoverer = new CachedDiscoverer($httpDiscoverer, $cache);
$metadata = $cachedDiscoverer->discover('https://accounts.google.com/.well-known/openid-configuration');
var_dump($metadata->issuer());
