<?php defined('SYSPATH') or die('No direct access allowed.');

return array(

	'driver'       => 'simple',
	'hash_method'  => FALSE,
	'hash_key'     => FALSE,
	'session_type' => Session::$default,
	'session_key'  => 'copo',

	// Username/password combinations for the Auth File driver
	'users' => array(
		// 'admin' => 'b3154acf3a344170077d11bdb5fff31532f679a1919e716a02',
	),

);
