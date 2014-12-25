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
        // @TODO: Think about caching of reading file
        $files = [
            'script-manifest.json',
            'style-manifest.json'
        ];

        $manifest = [];
        foreach ($files as $file) {
            $path = public_path('built/'.$file);
            $files = file_exists($path)
                ? json_decode(file_get_contents($path), true)
                : [];
            $manifest = array_merge($manifest, $files);
        }

        return array_key_exists($filename, $manifest)
            ? asset('built/'.$manifest[$filename])
            : $filename;
    }
}
