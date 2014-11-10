<?php namespace App\Appointment\NAT;

class Service
{
    /**
     * Get the next available timeslot of a user
     *
     * @param App\Core\Models\User $user
     *
     * @return Illuminate\Support\Collection
     */
    public function next($user)
    {
        dd($user);
    }

    /**
     * Build the calendar of business users in the given date
     *
     * @param App\Core\Models\User $user
     * @param Carbon\Carbon $date
     *
     * @return void
     */
    public function build($user, $date)
    {

    }
}
