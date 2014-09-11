<?php
namespace App\MarketingTool\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class GroupConsumer extends Eloquent
{
    protected $table = 'mt_group_consumers';
    
    public function consumer()
    {
        return $this->belongsTo('\App\MarketingTool\Models\Consumer', 'consumer_id');
    }
}
