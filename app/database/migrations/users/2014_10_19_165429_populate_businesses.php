<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Core\Models\Business;
use App\Core\Models\User;

class PopulateBusinesses extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $required = [
            'name',
            'size',
            'address',
            'city',
            'postcode',
            'country',
            'phone'
        ];

        foreach (User::all() as $user) {
            if (empty($user->business_name)) {
                continue;
            }

            $input = [
                'name' => $user->business_name,
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

            foreach ($required as $field) {
                if (empty($input[$field])) {
                    $input[$field] = 'N/A';
                }
            }

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
        Business::truncate();
    }

}
