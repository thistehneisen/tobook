<?php namespace App\Appointment\Models;

use Confide, DB;
use App\Core\Models\User;
use Hashids;

class Consumer extends \App\Consumers\Models\Consumer
{
    protected $rulesets = [
        'saving' => [
            'email'         => 'email',
            'first_name'    => 'required',
            'last_name'     => 'required',
            'phone'         => 'required|numeric'
        ]
    ];

    /**
     * Create a new consumer, overidde parent class for bypass parent validation
     *
     * @param array                    $data   Consumer's information will be saved
     * @param int|App\Core\Models\User $userId The ID of user this consumer
     *                                         belongs to
     *
     * @throws Watson\Validating\ValidationException If validation is not passed
     *
     * @return App\Consumers\Model
     */
    public static function make($data, $userId = null)
    {
        // If there is no information passed, get the current user
        $user = Confide::user();
        if ($userId instanceof \App\Core\Models\User) {
            $user = $userId;
        } elseif ((int) $userId > 0) {
            $user = User::findOrFail($userId);
        }

        if (empty($data['email'])) {
            $data['email'] = '';
        }

        try {
            $consumer = new self();
            $consumer->fill($data);
            $consumer->saveOrFail();
        } catch (\Watson\Validating\ValidationException $ex) {
            // Pass validation errors to other handler
            throw $ex;
        }

        $user->consumers()->attach($consumer->id);

        return $consumer;
    }

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

        $consumer = self::where('first_name', $first_name)
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

            if (empty($consumer->id)) {
                $consumer = self::make($data, $userId);
            } else {
                //TODO update consumer
                $consumer->fill($data);
                $consumer->saveOrFail();
            }
        } catch (\Watson\Validating\ValidationException $ex) {
            throw $ex;
        }
        return $consumer;
    }
}
