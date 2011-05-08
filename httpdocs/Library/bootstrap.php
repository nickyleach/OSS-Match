<?

function __autoload($class){
	require_once "Library/$class.php";
}

function die_dump(){
	foreach(func_get_args() as $arg){
		var_dump($arg);
	}

	exit;
}

function either(){
	foreach(func_get_args() as $arg){
		if($arg) return $arg;
	}
	
	return $arg;
}

?>