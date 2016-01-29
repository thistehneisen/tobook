<?php namespace App\Core\Auth;

use App\Core\Auth\AuthManager;

class AuthServiceProvider extends \Illuminate\Auth\AuthServiceProvider {

	 /**
     * {@inheritdoc}
     */
	public function register()
	{
		$this->app->bindShared('auth', function($app)
		{
			// Once the authentication service has actually been requested by the developer
			// we will set a variable in the application indicating such. This helps us
			// know that we need to set any queued cookies in the after event later.
			$app['auth.loaded'] = true;

			return new AuthManager($app);
		});
	}
}
