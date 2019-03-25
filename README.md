# CakePHP Datadog

CakePHP 2.x Datadog plugin.

## Installation

Install datadog php tracer extension:
https://docs.datadoghq.com/tracing/languages/php/

Install the plugin using composer:
```
composer require "snakano/cakephp-datadog:1.0.*"
```

## Usage

Load the plugin:
```
CakePlugin::load('Datadog');
```

Add the Dispatcher Filter to the `bootstrap.php` file:
```
Configure::write('Dispatcher.filters', array(
    'AssetDispatcher',
    'CacheDispatcher',
    'Datadog.DatadogFilter' // Add `DatadogFilter`
));
```

Add the application name to the `bootstrap.php` file:
```
Configure::write('Datadog.appName', 'My App Name');
```
