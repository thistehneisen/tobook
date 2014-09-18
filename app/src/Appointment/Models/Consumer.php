<?php namespace App\Appointment\Models;

use Confide, DB;
use App\Core\Models\User;

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

        $user->consumers()->attach($consumer->id, ['is_visible' => true]);

        return $consumer;
    }
}
