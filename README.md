# LOAD [![Build Status](https://travis-ci.com/taxibeat/load.svg?token=8cbpgLNGBxrmyFqzy42T&branch=master)](https://travis-ci.com/taxibeat/load)

**LOAD** is a PHP library for configuration loading to **APCu**

## Sources

Available sources for configuration loading are:

- PHP file
- Consul
- Environment variables

## Install

*TODO: Create package in packagist*

## Usage

PHP file:

```php
$loader = new BeatLabs\Loaders\PHPLoader('config.php'); // Config file
$config = new BeatLabs\Config([$loader]);
$config->load();

// Get configuration values
$val = $config->get('var');
```

Consul (Default server *localhost:8500*):

```php
$loader = new BeatLabs\Loaders\ConsulLoader();
$config = new BeatLabs\Config([$loader]);
$config->load();

// Get configuration values
$val = $config->get('var');
```

Consul (Custom server *myhost:8000* with root path "services/my-service"):

```php
$options = [
    "base_uri" => "myhost:8000"
];
$sf = new SensioLabs\Consul\ServiceFactory($options);
$kv = $sf->get(SensioLabs\Consul\Services\KVInterface::class);
$loader = new BeatLabs\Loaders\ConsulLoader('services/my-service', $kv);

$config = new BeatLabs\Config([$loader]);
$config->load();

// Get configuration values
$val = $config->get('var');
```

Environment variables:

```php
$loader = new BeatLabs\Loaders\EnvLoader('PREFIX_'); // Define environment variables prefix to be loaded
$config = new BeatLabs\Config([$loader]);
$config->load();

// Get configuration values
$val = $config->get('var');

```

Multiple loaders:
```php
$consulLoader = new BeatLabs\Loaders\ConsulLoader();
$fileLoader = new BeatLabs\Loaders\PHPLoader('config.php');
$envLoader = new BeatLabs\Loaders\EnvLoader('PREFIX_');
$config = new BeatLabs\Config([$consulLoader, $fileLoader, $envLoader]);
$config->load();

// Get configuration values
$val = $config->get('var');
```

Loaders are executed in order and they override any configuration load.ed from previous loaders.

## Reload configuration

Configuration can be reloaded by sending `SIGUSR2` to PHP running process for `CLI` scripts and in `php-fpm` master process for  `HTTP` scripts.

Reloading can also be invoked in code by using the following code:

```php
$config->reload();
```
