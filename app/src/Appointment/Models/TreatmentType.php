<?php namespace App\Appointment\Models;

use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;
use App\Appointment\Models\Reception\BackendReceptionist;

class TreatmentType extends \App\Appointment\Models\Base
{
    protected $table = 'as_treatment_types';

    public $fillable = [
        'name',
        'description',
    ];

    protected $rulesets = [
        'saving' => [
            'name' => 'required',
        ]
    ];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------

    public function services()
    {
        return $this->hasMany('App\Appointment\Models\Service');
    }
}
