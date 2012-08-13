<?php defined('SYSPATH') or die('No direct access allowed.');
class Auth_Simple extends Kohana_Auth_ORM
{

	public function logged_in($role = NULL)
	{
		$user = $this->get_user();
    return $user instanceof Model_Auth_Member AND $user->loaded();
	}

	protected function _login($user, $password, $remember)
	{
		if ( ! is_object($user))
		{
			$username = $user;

			$user = ORM::factory('copo');
			$user->where($user->unique_key($username), '=', $username)->find();
		}

		if (is_string($password))
		{
			$password = $this->hash($password);
		}

		if ($user->password === $password)
		{
			$this->complete_login($user);

			return TRUE;
		}

		return FALSE;
	}

	public function force_login($user, $mark_session_as_forced = FALSE)
	{
		if ( ! is_object($user))
		{
			$username = $user;

			// Load the user
			$user = ORM::factory('copo');
			$user->where($user->unique_key($username), '=', $username)->find();
		}

		if ($mark_session_as_forced === TRUE)
		{
			// Mark the session as forced, to prevent users from changing account information
			$this->_session->set('auth_forced', TRUE);
		}

		// Run the standard completion
		$this->complete_login($user);
	}

	public function get_user($default = NULL)
	{
		$user = parent::get_user($default);

		return $user;
	}

	public function logout($destroy = FALSE, $logout_all = FALSE)
	{
		// Set by force_login()
		$this->_session->delete('auth_forced');

		return parent::logout($destroy);
	}

 	public function hash($str)
 	{
 		if ($this->_config['hash_key']) {
      return hash_hmac($this->_config['hash_method'], $str, $this->_config['hash_key']);
    } else {
      return $str;
    }
 	}
} // End Auth_Simple
