<?

class Util {
	public static function execute($closure, $args = array()){
		call_user_func_array($closure, $args);
	}
	
	public static function redirect($url){
		header("Location: $url");
		exit;
	}
	
	public static function exec_script($script, $arg = null, $fork = false){
		$args = implode(" ", array_slice(func_get_args(), 1));
		$command = "sh ../shell/$script.sh $args " . ( $fork ? "&" : "" );
		$output;
		$return;
		
		exec($command, $output, $return);
		
		return compact('command', 'output', 'return');
	}
}

?>