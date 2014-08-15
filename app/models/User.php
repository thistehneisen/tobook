<?php
use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;

class User extends ConfideUser
{
    use HasRole;

    public $visible = [
        'id',
        'username',
        'email',
        'first_name',
        'last_name',
        'address_1',
        'address_2',
        'city',
        'state',
        'zipcode',
        'country',
        'phone',
        'fax',
        'stylesheet',
    ];

    /**
     * Allow old users to login with their own password, but force to change
     * immediately
     *
     * @param  string $identity Email/username
     * @param  string $password Plain password
     *
     * @return bool
     */
    public static function oldLogin($identity, $password)
    {
        $user = User::where(function ($query) use ($identity) {
            return $query->where('username', '=', $identity)
                ->orWhere('email', '=', $identity);
        })
        ->where('old_password', '=', md5($password))
        ->first();

        if ($user) {
            // Manually login
            App::make('auth')->login($user);
            
            return $user;
        }

        return false;
    }

    /**
     * Dump current user to native PHP session for other modules
     *
     * @return void
     */
    public function dumpToSession()
    {
        @session_start();
        $_SESSION['session_loginname'] = $this->username;
        $_SESSION['session_userid']    = $this->id;
        $_SESSION['session_email']     = $this->email;
        $_SESSION['session_style']     = $this->stylesheet;
        $_SESSION["owner_id"]          = $this->id;
    }

    /**
     * Remove old password to prevent login with this again
     *
     * @return bool
     */
    public function removeOldPassword()
    {
        $this->old_password = null;
        $this->save();
    }

    /**
     * Check if this user has been installed Cashier module
     *
     * @return boolean 
     */
    public function isCashierActivated()
    {
        $oldPrefix = DB::getTablePrefix();
        DB::setTablePrefix('sma_');

        $user = DB::table('users')->where('owner_id', '=', $this->id)->first();

        DB::setTablePrefix($oldPrefix);

        return (bool) $user;
    }

    /**
     * Check if this user has been installed TimeSlot booking module.
     *
     * @return boolean 
     */
    public function isTimeSlotActivated()
    {
        $oldPrefix = DB::getTablePrefix();
        DB::setTablePrefix('ts_');

        $user = DB::table('calendars')->where('owner_id', '=', $this->id)->first();

        DB::setTablePrefix($oldPrefix);

        return (bool) $user;
    }

    /**
     * Check if this user has been install Restaurant Booking module
     *
     * @return boolean 
     */
    public function isRestaurantBookingInstalled()
    {
        $oldPrefix = DB::getTablePrefix();
        DB::setTablePrefix('rb_');

        $user = DB::table('users')->where('owner_id', '=', $this->id)->first();

        DB::setTablePrefix($oldPrefix);

        return (bool) $user;
    }
}
