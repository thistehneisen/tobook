<?php namespace App\Appointment\Models;
use App\Appointment\Models\Consumer;

class AsConsumer extends \App\Core\Models\Base
{
    protected $table = 'as_consumers';

    public static function handleConsumer($data, $user = null)
    {
        $firstname = $data['firstname'];
        $lastname  = $data['lastname'];
        $email     = $data['email'];
        $phone     = $data['phone'];
        $hash      = $data['hash'];

        $consumer = Consumer::where('first_name', $firstname)
            ->where('last_name', $lastname)
            ->where('phone', $phone)->first();

        $asConsumer = new AsConsumer();

        //In front end, user is identified from hash
        $userId = null;
        if(empty($user)){
            $decoded = Hashids::decrypt($hash);
            if(empty($decoded)){
                return;
            }
            $user = User::find($decoded[0]);
            $userId = $decoded[0];
        }

        try{
            if (empty($consumer->id)) {
                $consumer = Consumer::make($data, $userId);
                $asConsumer->user()->associate($user);
                $asConsumer->consumer()->associate($consumer);
                $asConsumer->save();
            } else {
                //TODO update consumer
                $consumer->fill($data);
                $consumer->saveOrFail();
            }
        } catch(\Watson\Validating\ValidationException $ex){
            throw $ex;
        }
        return $consumer;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function consumer()
    {
       return $this->belongsTo('App\Consumers\Models\Consumer');
    }
}
