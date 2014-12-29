<?php

if (!function_exists('asset_path')) {
    /**
     * Return full path of a built asset file
     *
     * @param string $filename
     *
     * @return string
     */
    function asset_path($filename)
    {
        $folder = 'built/';

        // @TODO: Think about caching of reading file
        $path = base_path('rev-manifest.json');
        $manifest = file_exists($path)
            ? json_decode(file_get_contents($path), true)
            : [];

        return array_key_exists($filename, $manifest)
            ? asset($folder.$manifest[$filename])
            : asset($folder.$filename);
    }
}
