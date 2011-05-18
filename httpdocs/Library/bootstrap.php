<?

ini_set('display_errors', 'on');
error_reporting(E_ERROR);

function __autoload($class){
	$path = "Library/$class.php";
	
	if(file_exists($path))
		require_once $path;
}


define("UPLOAD_EXPIRE_TIME", 21600);

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

function require_if_exists($path){
	if(file_exists($path)) require_once $path;
	return class_exists(basename($path, '.php'), false);
}

// 3rd Party Functions
include 'vendors/preg_find.php';

?>