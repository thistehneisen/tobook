<?php
namespace App\MarketingTool\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Setting extends Eloquent
{
    protected $table = 'mt_settings';
    
    public function campaign()
    {
        return $this->belongsTo('Campaign', 'campaign_id');
    }    
    
    public function sms()
    {
        return $this->belongsTo('Sms', 'sms_id');
    }

}
