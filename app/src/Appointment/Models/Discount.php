<?php namespace App\Appointment\Models;

use Config, Util, Validator;
use Carbon\Carbon;

class Discount extends \Eloquent
{
    protected $table = 'as_discounts';

    public $fillable = [
        'start_at',
        'end_at',
        'weekday',
        'period',
        'discount'
    ];

    protected $rulesets = [
        'saving' => [
            'start_at' => 'required',
            'end_at'   => 'required',
            'weekday'  => 'required',
            'period'   => 'required',
            'discount' => 'required'
        ]
    ];

    public static function createFormData(&$data)
    {
        $user = $data['user'];

        $data['discount'][0] = trans('as.options.discount.full-price');
        for ($i=5; $i <= 100 ; $i+=5) {
            $data['discount'][$i] = sprintf('%d %%', $i);
        }

        for ($i=1; $i <= 24 ; $i++) {
            $data['hours'][$i] = sprintf('%d:00', $i);
        }

        $data['weekdays'] = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
        $data['periods']  = ['morning', 'afternoon', 'evening'];

        $discounts = self::where('user_id', '=', $user->id)->get();

        foreach ($discounts as $discount) {
            $data['me'][$discount->weekday][$discount->period] = $discount->discount;
        }

        $afternoon = self::where('user_id', '=', $user->id)
            ->where('period', '=', 'afternoon')->first();

        $data['afternoon'] = (!empty($afternoon->start_at))
            ? new Carbon($afternoon->start_at)
            : new Carbon('12:00:00');

        $evening = self::where('user_id', '=', $user->id)
            ->where('period', '=', 'evening')->first();

        $data['evening'] = (!empty($afternoon->start_at))
            ?  new Carbon($evening->start_at)
            :  new Carbon('17:00:00');

        return $data;
    }

    /**
     * Insert or update discount
     */
    public function upsert($data)
    {
        $user = $data['user'];

        //Need to validate data
        $eveningStart   = sprintf('%02d:00:00', $data['evening_starts_at']);
        $eveningEnd     = '23:59:00';
        $afternoonStart = sprintf('%02d:00:00', $data['afternoon_starts_at']);
        $afternoonEnd   = sprintf('%02d:00:00', $data['evening_starts_at']);
        $morningStart   = '00:00:00';
        $morningEnd     = sprintf('%02d:00:00', $data['afternoon_starts_at']);

        foreach ($data['discount'] as $weekday => $periods) {
            foreach ($periods as $period => $discount) {

                $obj = self::where('weekday', '=', $weekday)
                    ->where('period', '=', $period)
                    ->where('user_id', '=', $user->id)->first();

                if (empty($obj)) {
                    $obj = new self();
                }
                $start = sprintf('%sStart', $period);
                $end   = sprintf('%sEnd', $period);

                $obj->fill([
                    'weekday' => $weekday,
                    'period'  => $period,
                    'discount'=> $discount,
                    'start_at'=> $$start,
                    'end_at'  => $$end,
                ]);
                $obj->user()->associate($user);
                $obj->save();
            }
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
