<?php namespace App\Core;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\UserInterface;

class UserProvider extends EloquentUserProvider
{

    public function validateCredentials(UserInterface $user, array $credentials)
    {
        // let parent validate the password first, which should take more time
        // than simple hashing with md5 below
        $validated = parent::validateCredentials($user, $credentials);

        // fallback to old_password validation, if possible
        if (!$validated && !empty($user->old_password) && !empty($credentials['password'])) {
            $oldPassword = $user->old_password;
            $plain = $credentials['password'];
            $hash = md5($plain);

            if ($oldPassword === $hash) {
                $validated = true;
            }
        }

        return $validated;
    }

}
