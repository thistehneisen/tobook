<?php namespace App\Restaurant\Models;

use App\Core\Models\User;

class Option extends \App\Core\Models\Base
{
    protected $table = 'rb_options';

    public $fillable = ['key', 'value'];

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------

    public function getValueAttribute()
    {
        return json_decode($this->attributes['value'], true);
    }

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = json_encode($value);
    }

    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------

    /**
     * Update AS options of the provided user
     *
     * @param User  $user
     * @param array $dirty
     *
     * @return bool
     */
    public static function upsert(User $user, array $dirty)
    {
        $options = [];
        // Get all available options of this user
        $userOptions = [];
        foreach ($user->rbOptions()->get() as $item) {
            $userOptions[$item->key] = $item;
        }

        foreach ($dirty as $key => $value) {
            // Instead of going to database to check, we will use $userOptions
            $option = isset($userOptions[$key]) ? $userOptions[$key] : null;
            if ($option === null) {
                $option = new static();
            }

            $option->fill([
                'key'   => $key,
                'value' => $value,
            ]);

            $options[] = $option;
        }

        $user->rbOptions()->saveMany($options);
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Model\User');
    }
}
