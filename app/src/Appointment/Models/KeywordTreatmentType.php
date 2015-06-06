<?php namespace App\Appointment\Models;

use App;
use App\Core\Models\Multilanguage;
use Config;
use DB;
use Input;
use Str;

class KeywordTreatmentType extends \App\Core\Models\Base
{

    protected $table = 'as_keyword_treatment_type';

    public $fillable = ['keyword'];

    /**
     * @{@inheritdoc}
     */
    public $isSearchable = false;


    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------

    public function treatment()
    {
        return $this->belongsTo('App\Appointment\Models\TreatmentType');
    }
}
