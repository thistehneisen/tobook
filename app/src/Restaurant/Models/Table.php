<?php namespace App\Restaurant\Models;

class Table extends \App\Core\Models\Base
{
    protected $table = 'rb_tables';
    public $fillable = ['name', 'seats', 'minimum'];

    public function user()
    {
        return $this->belongsTo('App\Core\Model\User');
    }
}
