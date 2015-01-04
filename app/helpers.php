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
        $env = App::environment();
        $folder = 'built/';

        // Check the instance folder, if there's no folder existing, fallback
        // to the default one, which is `varaa`
        $instance = $env;
        if (!file_exists(public_path($folder.$env))) {
            $instance = 'varaa';
        }

        // @TODO: Think about caching of reading file
        $path = base_path('rev-manifest.json');
        $manifest = file_exists($path)
            ? json_decode(file_get_contents($path), true)
            : [];

        $filename = $instance.'/'.$filename;
        // dd($filename, $manifest);
        return array_key_exists($filename, $manifest)
            ? asset($folder.$manifest[$filename])
            : asset($folder.$filename);
    }
}
