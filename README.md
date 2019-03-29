# LOAD

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
$sf = new SensioLabs\Consul\ServiceFactory($options);
$kv = $sf->get(SensioLabs\Consul\Services\KVInterface::class);
$loader = new MOP\Loaders\ConsulLoader('services/my-service', $kv);

$config = new MOP\Config($loader);
$config->load();

// Get configuration values
$val = $config->get('var');
```
