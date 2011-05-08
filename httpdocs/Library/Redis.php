<?

class Redis {

	public static $cache = array();

	public static function __callStatic($name, $args) {
		
	}
	
	public static function exists($key){
		return isset(self::$cache[$key]);
	}
	
	public static function get($key){
		return self::$cache[$key];
	}
	
	public static function set($key, $val){
		self::$cache[$key] = $val;
	}
	
	public static function sadd($key, $member){
		self::$cache[$key][] = $member;
	}
	
	public static function members($key){
		return self::$cache[$key];
	}

}

?>