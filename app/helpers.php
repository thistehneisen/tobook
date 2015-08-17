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
        // In development environment, just use the file with no hash
        if (App::environment() === 'local') {
            return asset('assets/'.$filename);
        }

        // In other environments, assets come with hashes
        $path = base_path('rev.json');
        $manifest = file_exists($path)
            ? json_decode(file_get_contents($path), true)
            : [];

        return array_key_exists($filename, $manifest)
            ? asset('assets/'.$manifest[$filename])
            : asset('assets/'.$filename);
    }
}

if (!function_exists('show_money')) {
    /**
     * Show money with format and predefined currency
     *
     * @param  double $amount
     * @param  string $currency
     *
     * @return string
     */
    function show_money($amount, $currency = null, $decimals = 2)
    {
        $currency = $currency !== null ? $currency : Settings::get('currency');

        return number_format($amount, $decimals).$currency;
    }
}
