# LOAD [![Build Status](https://travis-ci.com/taxibeat/load.svg?token=8cbpgLNGBxrmyFqzy42T&branch=master)](https://travis-ci.com/taxibeat/load)

**LOAD** is a PHP library for configuration loading to **APCu**

## Sources

Available sources for configuration loading are:

- PHP file
- Consul
- Environment variables

## Install

You can install `load` using [Composer](https://getcomposer.org/) by running the following:

`composer require beatlabs/load`

## Usage

PHP file:

You can read a `PHP` file that returns an array. For example with `config.php`:
```php
return [
    'var1' => [
        'sub-var1' => 'sub-val1',
        'val1',
    ]   
];
```

You can use the following:
```php
$loader = new BeatLabs\Load\Loader\PHPLoader('config.php'); // Config file
$config = new BeatLabs\Load\Config([$loader]);
$config->load();

// Get configuration values
$val = $config->get('var');
```

Consul (Default server *localhost:8500*):

```php
$loader = new BeatLabs\Load\Loader\ConsulLoader();
$config = new BeatLabs\Load\Config([$loader]);
$config->load();

// Get configuration values
$val = $config->get('var');
```

Consul (Custom server *myhost:8000* with root path "services/my-service"):

```php
// You can get all available options here: https://docs.guzzlephp.org/en/6.5/quickstart.html#creating-a-client
$options = [
    "base_uri" => "myhost:8000"
];
$loader = new BeatLabs\Load\Loader\ConsulLoader('services/my-service', $options);

$config = new BeatLabs\Load\Config([$loader]);
$config->load();

// Get configuration values
$val = $config->get('var');
```

Environment variables:

You can have a prefix for environment variables so that you only include environment variables that start with that prefix. That gives the ability to load only what needed instead of entire environment as a configuration.

```php
// Set variable
$loader = new BeatLabs\Load\Loader\EnvLoader('PREFIX_'); // Define environment variables prefix to be loaded
$config = new BeatLabs\Load\Config([$loader]);
$config->load();

// Get configuration values
$val = $config->get('var');

```

Multiple loaders:
```php
$consulLoader = new BeatLabs\Load\Loader\ConsulLoader();
$fileLoader = new BeatLabs\Load\Loader\PHPLoader('config.php');
$envLoader = new BeatLabs\Load\Loader\EnvLoader('PREFIX_');
$config = new BeatLabs\Load\Config([$consulLoader, $fileLoader, $envLoader]);
$config->load();

// Get configuration values
$val = $config->get('var');
```

Loaders are executed in the order they are defined. Each loader will override any configuration loaded from previous loaders.

## Reload configuration

Configuration can be reloaded by sending `SIGUSR2` to PHP running process for `CLI` scripts and in `php-fpm` master process for  `HTTP` scripts.

Reloading can also be invoked in code by using the following code:

```php
$config->reload();
```

## How to Contribute

See [Contribution Guidelines](CONTRIBUTE.md)

## Code of conduct

Please note that this project is released with a [Contributor Code of Conduct](CODE_OF_CONDUCT.md). By participating in this project and its community you agree to abide by those terms.

## Changelog

You can see changelog [here](CHANGELOG.md)
