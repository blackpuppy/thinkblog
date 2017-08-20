<?php
if (! function_exists('mix')) {
    function starts_with($haystack, $needle)
    {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }
}

if (! function_exists('mix')) {
    /**
     * Get the path to a versioned Mix file.
     *
     * @param string $path
     * @param string $manifestDirectory
     * @return string
     *
     * @throws \Exception
     */
    function mix($path, $manifestDirectory = '')
    {
        static $manifest;
        $publicFolder = '/Public';
        $rootPath = $_SERVER['DOCUMENT_ROOT'];
        $publicPath = $rootPath . $publicFolder;
        if ($manifestDirectory && ! starts_with($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }

        // $msg = PHP_EOL . '$rootPath = ' . $rootPath
        //     . PHP_EOL . '$publicFolder = ' . $publicFolder
        //     . PHP_EOL . '$publicPath = ' . $publicPath
        //     . PHP_EOL . '$manifestDirectory = ' . $manifestDirectory
        //     . PHP_EOL . '$manifest = ' . print_r($manifest, true);

        if (! $manifest) {
            if (! file_exists($manifestPath = ($rootPath . $manifestDirectory.'/mix-manifest.json') )) {
                throw new Exception('The Mix manifest does not exist.');
            }
            $manifest = json_decode(file_get_contents($manifestPath), true);
        }
        if (! starts_with($path, '/')) {
            $path = "/{$path}";
        }
        // $path = $publicFolder . $path;

        // $msg .= PHP_EOL . '$path = ' . $path
        //     . PHP_EOL . '$manifest = ' . print_r($manifest, true);
        // \Think\Log::write($msg, 'INFO');

        if (! array_key_exists($path, $manifest)) {
            throw new Exception(
                "Unable to locate Mix file: {$path}. Please check your ".
                'webpack.mix.js output paths and try again.'
            );
        }
        return file_exists($publicPath . ($manifestDirectory.'/hot'))
                    ? "http://localhost:8084{$manifest[$path]}"
                    : $manifestDirectory.$manifest[$path];
    }
}
