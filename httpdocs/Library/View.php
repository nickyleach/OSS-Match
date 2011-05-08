<?

class View {
	protected $args;
	protected $path;
	
	public function __construct($path, $args = array()){
		$this->args = $args;
		$this->path = "Views/$path.php";
	}
	
	public function args(){
		return $this->args;
	}
	
	public function path(){
		return $this->path;
	}
}

?>