<?php namespace App\LoyaltyCard\Controllers;

use Auth, Confide, Validator, Request, Input;
use App\LoyaltyCard\Models\Consumer as Model;
use App\Consumers\Models\Consumer as Core;

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

    public function storeConsumer($isApi, &$validator = null)
    {
        $rules = [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'required|email|unique:consumers',
            'phone'         => 'required|numeric',
            'address'       => 'required',
            'postcode'      => 'required|numeric',
            'city'          => 'required',
            'country'       => 'required',
        ];

        $validator = $isApi ? Validator::make(Request::all(), $rules) : Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return null;
        } else {
            $data = [
                'first_name'    => $isApi ? Request::get('first_name') : Input::get('first_name'),
                'last_name'     => $isApi ? Request::get('last_name') : Input::get('last_name'),
                'email'         => $isApi ? Request::get('email') : Input::get('email'),
                'phone'         => $isApi ? Request::get('phone') : Input::get('phone'),
                'address'       => $isApi ? Request::get('address') : Input::get('address'),
                'postcode'      => $isApi ? Request::get('postcode') : Input::get('postcode'),
                'city'          => $isApi ? Request::get('city') : Input::get('city'),
                'country'       => $isApi ? Request::get('country') : Input::get('country'),
            ];

            $core = $isApi ? Core::make($data, Auth::user()->id) : Core::make($data, Confide::user()->id);

            $consumer = new Model;
            $consumer->total_points = 0;
            $consumer->total_stamps = '';
            $consumer->consumer_id = $core->id;
            $consumer->consumer()->associate($core);
            $consumer->save();

            return $consumer;
        }
    }
}
