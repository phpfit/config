<?php

namespace PhpFit\Config\Provider;

use PhpFit\Config\Interfaces\ConfigProviderInterface;
use PhpFit\File\FileSystem;

class LocalProvider implements ConfigProviderInterface
{
    public static function inject(array $configs, mixed $options): array
    {
        $directory = $options['directory'];
        $files = FileSystem::scan($directory);

        foreach ($files as $file) {
            if ($file == 'local.php') {
                continue;
            }
            $file_abs = $directory . '/' . $file;
            $file_base = basename($file, '.php');
            $file_conf = [$file_base => include $file_abs];

            $configs = array_replace_recursive($configs, $file_conf);
        }

        if (in_array('local.php', $files)) {
            $loc_conf = include $directory . '/local.php';
            $configs = array_replace_recursive($configs, $loc_conf);
        }

        return $configs;
    }
}
