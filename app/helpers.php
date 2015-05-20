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
        if (App::environment() === 'local') {
            return asset('assets/'.$filename);
        }
        $path = base_path('rev.json');
        $manifest = file_exists($path)
            ? json_decode(file_get_contents($path), true)
            : [];

        return array_key_exists($filename, $manifest)
            ? asset('assets/'.$manifest[$filename])
            : asset('assets/'.$filename);
    }
}
