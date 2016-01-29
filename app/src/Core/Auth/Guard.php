<?php namespace App\Core\Auth;

class Guard extends \Illuminate\Auth\Guard{

	 /**
     * {@inheritdoc}
     */
	public function user()
	{
		if ($this->loggedOut) return;

		// If we have already retrieved the user for the current request we can just
		// return it back immediately. We do not want to pull the user data every
		// request into the method because that would tremendously slow an app.
		if ( ! is_null($this->user))
		{
			return $this->user;
		}

		$id = $this->session->get($this->getName());
		
		if (empty($id) && !empty($_SESSION['session_userid'])) {
            $id = $_SESSION['session_userid'];
        }

		// First we will try to load the user using the identifier in the session if
		// one exists. Otherwise we will check for a "remember me" cookie in this
		// request, and if one exists, attempt to retrieve the user using that.
		$user = null;

		if ( ! is_null($id))
		{
			$user = $this->provider->retrieveByID($id);
		}

		// If the user is null, but we decrypt a "recaller" cookie we can attempt to
		// pull the user data on that cookie which serves as a remember cookie on
		// the application. Once we have a user we can return it to the caller.
		$recaller = $this->getRecaller();

		if (is_null($user) && ! is_null($recaller))
		{
			$user = $this->getUserByRecaller($recaller);
		}

		return $this->user = $user;
	}
}