<?php namespace App\LoyaltyCard\Controllers;

use Auth, Confide;
use App\LoyaltyCard\Models\Consumer as Model;

class ConsumerRepository
{
    public function getAllConsumers($isApi, $search = null)
    {
        if ($isApi) {
            $consumers = Model::join('consumers', 'lc_consumers.consumer_id', '=', 'consumers.id')
                        ->join('consumer_user', 'lc_consumers.consumer_id', '=', 'consumer_user.consumer_id')
                        ->where('consumer_user.user_id', Auth::user()->id)
                        ->get();
        } else {
            if ($search != null) {
                $consumers = Model::join('consumers', 'lc_consumers.consumer_id', '=', 'consumers.id')
                                ->join('consumer_user', 'lc_consumers.consumer_id', '=', 'consumer_user.consumer_id')
                                ->where('consumer_user.user_id', Confide::user()->id)
                                ->where(function($q) use ($search) {
                                    $q->where('consumers.first_name', 'like', '%' . $search . '%')
                                        ->orWhere('consumers.last_name', 'like', '%' . $search . '%')
                                        ->orWhere('consumers.email', 'like', '%' . $search . '%')
                                        ->orWhere('consumers.phone', 'like', '%' . $search . '%');
                                })
                                ->select('lc_consumers.id', 'consumers.first_name', 'consumers.last_name', 'consumers.email', 'consumers.phone', 'lc_consumers.updated_at')
                                ->paginate(10);
            } else {
                $consumers = Model::join('consumers', 'lc_consumers.consumer_id', '=', 'consumers.id')
                                ->join('consumer_user', 'lc_consumers.consumer_id', '=', 'consumer_user.consumer_id')
                                ->where('consumer_user.user_id', Confide::user()->id)
                                ->select('lc_consumers.id', 'consumers.first_name', 'consumers.last_name', 'consumers.email', 'consumers.phone', 'lc_consumers.updated_at')
                                ->paginate(10);
            }
        }

        return $consumers;
    }
}
