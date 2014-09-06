<?php namespace App\Core\Models;

use Config, Confide;

class Image extends Base
{
    public $fillable = ['path', 'type', 'imageable_id', 'imageable_type'];

    const BUSINESS_IMAGE = 'business-image';

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function imageable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    //--------------------------------------------------------------------------
    // SCOPES
    //--------------------------------------------------------------------------

    public function scopeBusinessImages($query)
    {
        return $query->where('type', static::BUSINESS_IMAGE);
    }

    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------
    public function getPublicUrl()
    {
        return asset(
            Config::get('varaa.upload_folder').$this->path
        );
    }

    public static function getBusinessImageData()
    {
        return [
            'imageable_id'   => Confide::user()->id,
            'imageable_type' => get_class(Confide::user()),
            'type'           => static::BUSINESS_IMAGE
        ];
    }
}
