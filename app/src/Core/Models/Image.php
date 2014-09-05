<?php namespace App\Core\Models;

class Image extends Base
{
    public $fillable = ['path', 'imageable_id', 'imageable_type'];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function imageable()
    {
        return $this->morphTo();
    }
}
