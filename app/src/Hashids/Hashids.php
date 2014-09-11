<?php namespace App\Hashids;

class Hashids
{
    protected $instance;

    public function __construct($key, $length, $alphabet)
    {
        $this->instance = new \Hashids\Hashids(
            $key,
            $length,
            $alphabet
        );
    }

    public function encrypt()
    {
        return call_user_func_array([$this->instance, 'encode'], func_get_args());
    }

    public function decrypt($hash)
    {
        return $this->instance->decode($hash);
    }
}
