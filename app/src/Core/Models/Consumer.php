<?php namespace App\Core\Models;

class Consumer extends \Eloquent
{
    /**
     * Concat first_name and last_name
     * Usage: $user->name
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }
}
