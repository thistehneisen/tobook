<?php namespace App\Core\Models;

use App\Core\Models\User;

class Multilanguage extends Base
{
    protected $table = 'multilanguage';

    public $fillable = [
        'context',
        'lang',
        'key',
        'value'
    ];

    public $rulesets = [
        'saving' => [
            'context' => 'required',
            'lang' => 'required',
            'key' => 'required',
        ]
    ];

    /**
     * @overload
     */
    public static function bootSoftDeletingTrait()
    {
        // Overwrite to disable SoftDeleting
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

}
