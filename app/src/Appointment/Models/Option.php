<?php namespace App\Appointment\Models;

use App\Core\Models\User;

class Option extends BaseModel
{
    protected $table = 'as_options';

    public $fillable = ['key', 'value'];

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
        foreach ($dirty as $key => $value)
        {
            $option = static::where('key', $key)->first();
            if ($option === null) {
                $option = new static;
            }

            $option->fill([
                'key'   => $key,
                'value' => $value,
            ]);

            $options[] = $option;
        }

        $user->asOptions()->saveMany($options);
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Model\User');
    }
}
