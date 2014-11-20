<?php
namespace App\Consumers\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Group extends \App\Core\Models\Base
{
    public $fillable = ['name'];

    protected $table = 'mt_groups';

    protected $rulesets = ['saving' => [
        'name' => 'required',
    ]];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function consumers()
    {
        return $this->belongsToMany('App\Consumers\Models\Consumer', 'mt_group_consumers');
    }
}
