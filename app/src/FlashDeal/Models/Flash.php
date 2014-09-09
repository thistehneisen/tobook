<?php
namespace App\FlashDeal\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Flash extends Eloquent
{
    protected $table = 'fd_flashs';
    
    public function service()
    {
        return $this->belongsTo('\App\FlashDeal\Models\Service', 'service_id');
    }
    
    public function flashTimes()
    {
        return $this->hasMany('\App\FlashDeal\Models\FlashTime', 'flash_id');
    }    
}
