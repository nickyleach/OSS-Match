<?

class Repository {
	protected $files;
	protected $name;
	protected $status;

	public function __construct($name){
		$this->files = array();
		$this->name = $name;
	}
	
	public function add($id){
		$this->files[] = $id;
		Redis::sadd("Repository::$name", $id);
	}
	
	public function features(){
		$features = array();
		foreach($this->files() as $id){
			$source = new Source($id);
			foreach($source->features as $attribute => $value){
				$features[$source->type][$attribute] += $value;
			}
		}
		
		// Average all the attributes
		foreach($features as $type => $attributes){
			foreach($attributes as $attribute => $value){
				$features[$type][$attribute] = ($value / count($this->files()));
			}
		}
		
		return $features;
	}
	
	public function files(){
		if(empty($this->files)) $this->files = Redis::smembers("Repository::{$this->name}");
		
		return $this->files;
	}
}

?>