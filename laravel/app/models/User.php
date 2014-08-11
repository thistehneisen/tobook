<?php
use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;

class User extends ConfideUser
{
    use HasRole;
    const CHANGE_PASSWORD_SESSION_NAME = 'forceChangePassword';

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
        $user = User::where(function($query) use ($identity) {
            return $query->where('username', '=', $identity)
                ->orWhere('email', '=', $identity);
        })
        ->where('old_password', '=', md5($password))
        ->first();

        if ($user) {
            // Set session to force change password
            Session::put(User::CHANGE_PASSWORD_SESSION_NAME, true);

            // Manually login
            App::make('auth')->login($user);
            
            return $user;
        }

        return false;
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
}
