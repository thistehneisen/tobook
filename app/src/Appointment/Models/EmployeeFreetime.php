<?php namespace App\Appointment\Models;
use Carbon\Carbon;
use Config;
class EmployeeFreetime extends \App\Core\Models\Base
{
    protected $table = 'as_employee_freetime';

    public $fillable = ['date', 'start_at', 'end_at', 'description'];


       //Currently don't use attribute because can break other code
    public function getStartAt()
    {
        $startAt =  Carbon::createFromFormat('H:i:s', $this->start_at, Config::get('app.timezone'));
        return $startAt;
    }

    //Currently  don't use attribute because can break other code
    public function getEndAt()
    {
        $endAt =  Carbon::createFromFormat('H:i:s', $this->end_at, Config::get('app.timezone'));
        return $endAt;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function employee()
    {
        return $this->belongsTo('App\Appointment\Models\Employee');
    }

}
