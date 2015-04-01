<?php namespace App\Appointment\Models;

class MasterCategory extends \App\Core\Models\Base
{
    protected $table = 'as_master_categories';

    protected $dates = ['deleted_at'];

    public $fillable = ['name', 'description'];

    protected $rulesets = [
        'saving' => [
            'name' => 'required'
        ]
    ];

    public function isDeletable()
    {
        return ($this->services->isEmpty()) ? true : false;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function services()
    {
        return $this->hasMany('App\Appointment\Models\Service', 'master_category_id');
    }
}
