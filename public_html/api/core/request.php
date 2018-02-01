<?php	
	/**
	-------------------------------------
	| Request Manager
	-------------------------------------
	*/	
	
	class Request{
		/** ----------------------------------
		| check method
		| @return: string
		----------------------------------- **/	
		public static function method(){
			return $_SERVER['REQUEST_METHOD'];
		}
		
		/** ----------------------------------
		| check uri
		| @return: string
		----------------------------------- **/	
		public static function uri(){
			return trim(parse_url($_SERVER['REQUEST_URI'])['path'], '/');
		}
	}
?>