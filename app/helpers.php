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

if (!function_exists('str_datetime_format')) {
    function str_datetime_format()
    {
        return 'd.m.Y (H:i)';
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
        return $date->format(str_datetime_format());
    }
}

if (!function_exists('str_standard_to_local')) {
    function str_standard_to_local($str, $type = 'date')
    {
        $date = new Carbon\Carbon($str);
        return ($type === 'date')
            ? $date->format(str_date_format())
            : $date->format(str_datetime_format());
    }
}

if (!function_exists('sms_normalize')) {
    function sms_normalize($str)
    {
        $original = $str;

        $str = preg_replace('@\x{00101}@u', "a", $str);// ā => a
        $str = preg_replace('@\x{0010D}@u', "c", $str);// č => c
        $str = preg_replace('@\x{00113}@u', "e", $str);// ē => e
        $str = preg_replace('@\x{00123}@u', "g", $str);// ģ => g
        $str = preg_replace('@\x{0012B}@u', "i", $str);// ī => i
        $str = preg_replace('@\x{00137}@u', "k", $str);// ķ => k
        $str = preg_replace('@\x{0013C}@u', "l", $str);// ļ => l
        $str = preg_replace('@\x{00146}@u', "n", $str);// ņ => n
        $str = preg_replace('@\x{00161}@u', "s", $str);// š => s
        $str = preg_replace('@\x{0016B}@u', "u", $str);// ū => u
        $str = preg_replace('@\x{0017E}@u', "z", $str);// ž => z

        if (empty($str))
            return $original;
        else
            return $str; 
    }
}

if (!function_exists('make_validation_exception')) {
    function make_validation_exception($key, $msg = '')
    {
        $messageBag = new \Illuminate\Support\MessageBag;
        $messageBag->add($key, $msg);
        $exception = new \Watson\Validating\ValidationException;
        $exception->setErrors($messageBag);
        return $exception;
    }
}

if (!function_exists('is_delfi_proxy')) {
    function is_delfi_proxy()
    {
       return in_array($_SERVER['REMOTE_ADDR'], ['62.63.137.2','62.63.137.4', '62.63.137.6', '62.63.137.205']);
    }
}

if (!function_exists('session_has')) {
    function session_has($key)
    {
        return Session::has($key);
    }
}

if (!function_exists('session_get')) {
    /**
     * Using for tobook under proxy
     * @param $key string
     * @return  mix
     */
    function session_get($key)
    {
        $default = null;
        return Session::get($key, $default);
    }
}
