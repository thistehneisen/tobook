<?php namespace App\Core\Models;

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

        if (empty($multilang)) {
            $multilang = new Multilanguage();
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

    /**
     * Remove multilanguage for specific user_id, context, language and key
     * @param $user_id int
     * @param $context string
     * @param $lang string
     * @param $key string
     */
    public static function remove($user_id, $context, $lang, $key)
    {
        $query = Multilanguage::where('context', '=', $context)->where('key', '=' , $key);

        if (!empty($user_id)) {
            $query = $query->where('user_id', '=', $user_id);
        }
        if (!empty($lang)) {
            $query = $query->where('lang', '=', $lang);
        }

        $multilangs = $query->get();

        foreach ($multilangs as $multilang) {
            $multilang->forceDelete();
        }
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

}
