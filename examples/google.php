<?php

declare(strict_types=1);

use DigitalCz\OpenIDConnect\Discovery\DiscovererFactory;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

require dirname(__DIR__) . '/vendor/autoload.php';

$cachePool = new FilesystemAdapter();
$cache = new Psr16Cache($cachePool);
$factory = new DiscovererFactory(cache: $cache);
$discoverer = $factory->create();
$metadata = $discoverer->discover('https://accounts.google.com/.well-known/openid-configuration');
var_dump($metadata);
