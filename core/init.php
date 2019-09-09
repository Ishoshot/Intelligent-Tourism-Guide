<?php
	session_start();
$GLOBALS['config'] = array(
	'mysql'=> array(
			'host' => '127.0.0.1',
			'username' => 'root',
			'password' => '',
			'db' => 'tourism'
	),

	'remember' => array(
		'cookie_name' => 'hash',
		'cookie_expiry' =>604800
		),

	'session'=> array(
		'session_name'=>'users',
		'token_name' => 'token'
	)
);
spl_autoload_register(function($class){
	require_once 'classes/' .$class . '.php';
});
require_once 'functions/sanitize.php';


if (Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
	# code...
	// echo 'User asked to be remembered!';

	

	$hash  = Cookie::get(Config::get('remember/cookie_name'));
	$hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));

	if ($hashCheck->count()) {
	 		# code...

		$user = new User($hashCheck->first()->user_id);
		$user->login();
	 	} 	
}
?>