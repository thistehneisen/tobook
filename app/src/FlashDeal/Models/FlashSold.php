<?php
namespace App\FlashDeal\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class FlashSold extends Eloquent
{
    protected $table = 'fd_flash_solds';
    
    public function flash()
    {
        return $this->belongsTo('\App\FlashDeal\Models\Flash', 'flash_id');
    }
    
    public function consumer()
    {
        return $this->belongsTo('\App\FlashDeal\Models\Consumer', 'consumer_id');
    }    
}
