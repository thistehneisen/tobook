<?php
namespace App\FlashDeal\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Service extends Eloquent
{
    protected $table = 'fd_services';
    
    public function category()
    {
        return $this->belongsTo('\App\FlashDeal\Models\ServiceCategory', 'category_id');
    }
}
