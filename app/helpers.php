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

        $filepath = $instance.'/'.$filename;

        // if (array_key_exists($filepath, $manifest)) {
        //     return asset($folder.$manifest[$filepath]);
        // } else {
        //     // @HACK: hack for tobook.lv for now
        //     if (!file_exists($folder.$filepath)) {
        //         return asset($folder.'varaa/'.$filename);
        //     }
        //     return asset($folder.$filepath);
        //     //-- end HACK
        // }

        return array_key_exists($filepath, $manifest)
            ? asset($folder.$manifest[$filepath])
            : asset($folder.$filepath);
    }
}
