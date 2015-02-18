<?php namespace App\Core\Models;

class Setting extends \Eloquent
{
    protected $primaryKey = 'key';
    public $incrementing  = false;
    public $timestamps    = false;
    protected $fillable   = ['key', 'value'];
}
