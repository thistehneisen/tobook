<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Core\Models\Business;

class MigrateBusiness extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (User::all() as $user) {
            if (!empty($user->business->name) || !empty($user->consumer_id)) {
                continue;
            }

            $input = [
                'name' => $user->username,
                'description' => $user->description,
                'size' => $user->business_size,
                'address' => $user->address,
                'city' => $user->city,
                'postcode' => $user->postcode,
                'country' => $user->country,
                'phone' => $user->phone,
                'lat' => $user->lat,
                'lng' => $user->lng,
            ];

            array_walk($input, function (&$value, $key) {
                if (empty($value)) {
                    if ($key === 'phone' || $key === 'name') {
                        $value = 'N/A';
                    } else {
                        $value = '';
                    }
                }
            });

            $business = new Business($input);
            $business->is_activated = true;
            $business->user()->associate($user);
            $business->saveOrFail();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
