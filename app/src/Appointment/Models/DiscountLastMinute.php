<?php namespace App\Appointment\Models;

use Config, Util, Validator;
use Carbon\Carbon;

class DiscountLastMinute extends \Eloquent
{
    public $primaryKey  = 'user_id';

    protected $table = 'as_last_minute_discounts';

    public $fillable = [
        'is_active',
        'before',
        'discount'
    ];

    protected $rulesets = [
        'saving' => [
            'is_active'=> 'required',
            'before'   => 'required',
            'discount' => 'required'
        ]
    ];

    /**
     * Create data params (e.g.:discount percentage, hours) for displaying on view
     *
     * @return array
     */
    public static function createFormData(&$data)
    {
        for ($i=5; $i <= 100 ; $i+=5) {
            $data['discount'][$i] = sprintf('%d %%', $i);
        }

        for ($i=1; $i <= 24 ; $i++) {
            $data['before'][$i] = sprintf('%d %s', $i, trans('as.options.discount.business-hours'));
        }

        $user = $data['user'];
        $me   = self::find($user->id);
        if (!empty($me)) {
            $data['me'] = $me;
        }
        return $data;
    }

    /**
     * Insert or update discount last minute record
     */
    public function upsert($data)
    {
        $user = $data['user'];
        $me   = self::find($user->id);

        if (empty($me)) {
            $me = new self();
        }

        $me->fill($data);
        $me->user()->associate($user);
        $me->save();
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }
}
