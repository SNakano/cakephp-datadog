# CakePHP Datadog
[![Latest Stable Version](https://poser.pugx.org/snakano/cakephp-datadog/v/stable)](https://packagist.org/packages/snakano/cakephp-datadog)
[![Total Downloads](https://poser.pugx.org/snakano/cakephp-datadog/downloads)](https://packagist.org/packages/snakano/cakephp-datadog)
[![License](https://poser.pugx.org/snakano/cakephp-datadog/license)](https://packagist.org/packages/snakano/cakephp-datadog)

CakePHP 2.x Datadog plugin.

## Installation

Install datadog php tracer extension:\
https://docs.datadoghq.com/tracing/languages/php/

Install the plugin using composer:
```shell
composer require "snakano/cakephp-datadog:1.0.*"
```

## Usage

Load the plugin:
```php
CakePlugin::load('Datadog');
```

Add the Dispatcher Filter to the `bootstrap.php` file:
```php
Configure::write('Dispatcher.filters', array(
    'AssetDispatcher',
    'CacheDispatcher',
    'Datadog.DatadogFilter' // Add `DatadogFilter`
));
```

Add the service name to the `bootstrap.php` file:
```php
Configure::write('Datadog.serviceName', 'My App Name');
```
