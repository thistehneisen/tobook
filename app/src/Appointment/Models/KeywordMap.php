<?php namespace App\Appointment\Models;

use App;
use App\Core\Models\Multilanguage;
use Config;
use DB;
use Input;
use Str;

class KeywordMap extends \App\Core\Models\Base
{

    protected $table = 'as_keyword_map';

    public $fillable = ['keyword'];

    protected $softDelete = false;
    /**
     * @{@inheritdoc}
     */
    public $isSearchable = false;

     //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function getSelectedAttribute()
    {
        $selected = '';
        if (!empty($this->attributes['treatment_type_id'])) {
            $selected =  'tm:' . $this->attributes['treatment_type_id'];
        } else if(!empty($this->attributes['master_category_id'])){
            $selected = 'mc:' . $this->attributes['master_category_id'];
        }

        return $selected;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------

    public function treatmentType()
    {
        return $this->belongsTo('App\Appointment\Models\TreatmentType');
    }

    public function masterCategory()
    {
        return $this->belongsTo('App\Appointment\Models\MasterCategory');
    }
}
