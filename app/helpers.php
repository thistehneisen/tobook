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

        return number_format((double) $amount, $decimals).$currency;
    }
}

if (!function_exists('is_tobook')) {
    function is_tobook()
    {
        return App::environment() === 'tobook';
    }
}

if (!function_exists('str_date_format')) {
    function str_date_format()
    {
        return 'd.m.Y';
    }
}

if (!function_exists('carbon_date')) {
    function carbon_date($str)
    {
        return Carbon\Carbon::createFromFormat(str_date_format(), $str);
    }
}

if (!function_exists('str_standard_date')) {
    function str_standard_date($str)
    {
        $date = carbon_date($str);
        return $date->toDateString();
    }
}

if (!function_exists('str_date')) {
    function str_date(Carbon\Carbon $date)
    {
        return $date->format(str_date_format());
    }
}

if (!function_exists('str_datetime')) {
    function str_datetime(Carbon\Carbon $date)
    {
        return $date->format('d.m.Y (H:i)');
    }
}

if (!function_exists('str_standard_to_local')) {
    function str_standard_to_local($str)
    {
        $date = carbon_date($str);
        return $date->format(str_date_format());
    }
}
