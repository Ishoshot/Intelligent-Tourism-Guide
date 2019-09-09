<?php 
class Hash{
	public static function make($string, $salt = ''){
			return hash('sha256', $string . $salt);
	}

	public static function salt($lenght) {
		return random_bytes($lenght); 
	}

	public static function unique(){
		return self::make(uniqid());
	}
}

?>