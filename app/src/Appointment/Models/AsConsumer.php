<?php namespace App\Appointment\Models;

use App\Core\Models\User;
use App\Appointment\Models\Consumer;
use Hashids;

class AsConsumer extends \App\Core\Models\Base
{
    protected $table = 'as_consumers';

    /**
     * @param $data
     * @param null $user
     * @return \App\Appointment\Models\Consumer
     */
    public static function handleConsumer($data, $user = null)
    {
        $first_name = trim($data['first_name']);
        $last_name  = trim($data['last_name']);
        $phone      = trim($data['phone']);
        $hash       = $data['hash'];

        $consumer = Consumer::where('first_name', $first_name)
            ->where('last_name', $last_name)
            ->where('phone', $phone)->first();

        //In front end, user is identified from hash
        $userId = null;
        if (empty($user)) {
            $decoded = Hashids::decrypt($hash);
            if (empty($decoded)) {
                return;
            }
            $user = User::find($decoded[0]);
            $userId = $decoded[0];
        } else {
            $userId = $user->id;
        }

        try {
            $asConsumer = null;

            if (empty($consumer->id)) {
                $consumer = Consumer::make($data, $userId);
            } else {
                //TODO update consumer
                $consumer->fill($data);
                $consumer->saveOrFail();

                $asConsumer = AsConsumer::where('user_id', $user->id)
                    ->where('consumer_id', $consumer->id)
                    ->first();
            }

            if (empty($asConsumer)) {
                $asConsumer = new AsConsumer();
                $asConsumer->user()->associate($user);
                $asConsumer->consumer()->associate($consumer);
                $asConsumer->saveOrFail();
            }
        } catch (\Watson\Validating\ValidationException $ex) {
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
