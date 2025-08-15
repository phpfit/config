<?php

namespace PhpFit\Config;

use PhpFit\Config\Interfaces\ConfigProviderInterface;
use PhpFit\Config\Provider\LocalProvider;
use Psr\SimpleCache\CacheInterface;

class Config
{
    private array $configs;

    /**
     * array settings
     *  ?string $directory
     *  ?array[ConfigProviderInterface *] $providers
     *  ?CacheInterface $cache
     *  ?string $cache_name = 'phpfit-config'
     */
    private array $settings;

    public function __construct(array $settings)
    {
        if (isset($settings['directory'])) {
            $providers = [
                LocalProvider::class => [
                    'directory' => $settings['directory']
                ]
            ];

            if (isset($settings['providers'])) {
                $providers = array_merge($providers, $settings['providers']);
            }

            $settings['providers'] = $providers;
        }
        $this->settings = $settings;

        $this->load();
    }

    public function __get(string $key): mixed
    {
        return $this->configs[$key] ?? null;
    }

    public function getCache(): ?CacheInterface
    {
        $cache = $this->settings['cache'] ?? null;
        if (!$cache) {
            return null;
        }

        if ($cache instanceof CacheInterface) {
            return $cache;
        }

        return null;
    }

    public function getCacheName(): string
    {
        return $this->settings['cache_name'] ?? 'phpfit-config';
    }

    public function load(): void
    {
        $configs = [];
        $cache = $this->getCache();
        $name = $this->getCacheName();
        if ($cache) {
            $configs = $cache->get($name);
            if ($configs) {
                $this->configs = $configs;
                return;
            }
        }

        $this->refresh();
    }

    public function refresh()
    {
        $providers = $this->settings['providers'] ?? [];
        $configs = [];
        $cache = $this->getCache();

        foreach ($providers as $provider => $options) {
            $configs = $provider::inject($configs, $options);
        }

        if ($cache) {
            $name = $this->getCacheName();
            $cache->set($name, $configs);
        }

        $this->configs = $configs;
    }
}
