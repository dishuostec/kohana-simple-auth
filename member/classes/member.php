<?php defined('SYSPATH') or die('No direct access allowed.');

abstract class Member extends Kohana_Auth
{
	public static function instance()
	{
		if ( ! isset(Member::$_instance))
		{
			// Load the configuration for this type
			$config = Kohana::$config->load('member');

			if ( ! $type = $config->get('driver'))
			{
				$type = 'simple';
			}

			// Set the session class name
			$class = 'Auth_'.ucfirst($type);

			// Create a new session instance
			Member::$_instance = new $class($config);
		}

		return Member::$_instance;
	}

	public function hash($str)
	{
		if ($this->_config['hash_key'])
    {
      return hash_hmac($this->_config['hash_method'], $str, $this->_config['hash_key']);
    } else {
      return $str;
    }
	}


}
