<?

class FileSystem {
	public static function files($dir, $recursive = false){
		return preg_find('/./', $dir, ( $recursive ? PREG_FIND_RECURSIVE : false ));
	}
}

?>