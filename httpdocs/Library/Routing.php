<?

class Routing {
	protected static $qs;
	
	public static function action(){
		if(!self::$qs) self::$qs = explode('/', $_GET['q']);
		
		return either(self::$qs[1], null);
	}
	
	public static function args(){
		if(!self::$qs) self::$qs = explode('/', $_GET['q']);
		
		return either(array_slice(self::$qs, 1), array());
	}
	
	public static function controllerName(){
		if(!self::$qs) self::$qs = explode('/', $_GET['q']);
		
		return either(self::$qs[0], 'Landing');
	}
}

?>