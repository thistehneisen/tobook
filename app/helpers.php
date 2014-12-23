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
        $manifest = base_path('rev-manifest.json');

        $manifest = file_exists($manifest)
            ? json_decode(file_get_contents($manifest), true)
            : [];

        return array_key_exists($filename, $manifest)
            ? asset('built/'.$manifest[$filename])
            : $filename;
    }
}
