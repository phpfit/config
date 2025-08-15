<?php

namespace PhpFit\Config\Interfaces;

interface ConfigProviderInterface
{
    public static function inject(array $configs, mixed $options): array;
}
