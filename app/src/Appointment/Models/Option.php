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
        // Get all available options of this user
        $userOptions = [];
        foreach ($user->asOptions()->get() as $item) {
            $userOptions[$item->key] = $item;
        }

        foreach ($dirty as $key => $value)
        {
            // Instead of going to database to check, we will use $userOptions
            $option = isset($userOptions[$key]) ? $userOptions[$key] : null;
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
