# phpfit/config

Collect and provide configs from local files or config providers

## Installation

```bash
composer require phpfit/config
```

## Usage

```php
use PhpFit\Config\Config;

$config = new Config($options);
$host = $config->host;
```

## Options

### ?string $directory

Directory to get all local php config file. Base name of the file will be used as
config key, while the content on the file as value.

File `local.php` is a special file that will be used as replacer for all other
predefined configs.

### ?array/ConfigProviderInterface $providers

List of config provider to call on getting the configs

### ?CacheInterface $cache

The cache driver to use to cache the config.

### ?string $cache_name = 'phpfit-config'

Default cache name to use to store the final configs.

## Interfaces

### PhpFit\Config\Interfaces\ConfigProviderInterface

An interface to use for custom config provider.

#### public static function inject(array $configs, mixed $options): array

This method get called for every time the config generated. The method should
return new configs to use.

## Providers

### PhpFit\Config\Provider\LocalProvider

The config provider for local file.

## License

The phpfit/env library is licensed under the MIT license.
See [License File](LICENSE.md) for more information.
