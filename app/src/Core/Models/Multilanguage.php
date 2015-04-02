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
     * Save multiple values to multilanguage table for specific context, language and key
     */
    public static function saveValues($object_id, $context, $key, $data)
    {
        foreach ($data as $lang => $value) {
            static::saveValue($object_id, $context, $lang, $key, $value);
        }
    }

    /**
     * Save value to multilanguage table for specific context, language and key
     */
    public static function saveValue($object_id, $context, $lang, $key, $value)
    {
        $multilang = Multilanguage::where('lang', '=', $lang)
                ->where('context','=', $context . $object_id)
                ->where('key', '=' , $key)->first();

        if(empty($multilang)) {
            $multilang = new Multilanguage;
        }

        $multilang->fill([
            'context' => $context . $object_id,
            'lang' => $lang,
            'key' => $key,
            'value' => $value
        ]);

        $multilang->save();
    }

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
