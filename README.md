# LOAD [![Build Status](https://travis-ci.com/taxibeat/load.svg?token=8cbpgLNGBxrmyFqzy42T&branch=master)](https://travis-ci.com/taxibeat/load)

**LOAD** is a PHP library for configuration loading to **APCu**

## Sources

Available sources for configuration loading are:

- PHP file
- Consul

## Install

*TODO: Create package in packagist*

## Usage

PHP file:

```php
$loader = new MOP\Loaders\PHPLoader('config.php');
$config = new MOP\Config($loader);
$config->load();

// Get configuration values
$val = $config->get('var');
```

Consul (Default server *localhost:8500*):

```php
$loader = new MOP\Loaders\ConsulLoader();
$config = new MOP\Config($loader);
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
$loader = new MOP\Loaders\ConsulLoader('services/my-service', $kv);

$config = new MOP\Config($loader);
$config->load();

// Get configuration values
$val = $config->get('var');
```

## Reload configuration

Configuration can be reloaded by sending `SIGUSR2` to PHP running process for `CLI` scripts and in `php-fpm` master process for  `HTTP` scripts.

Reloading can also be invoked in code by using the following code:

```php
$config->reload();
```
