<?

class Repository {
	public $id;
	public $files;
	public $name;
	public $url;

	public function __construct($id){
		$this->load($id);
	}
	
	public function add($id){
		if(Source::exists($id)){
			$this->files[] = $id;
			Redis::sadd("Repository.Source::{$this->id}", $id);
		}
	}
	
	public static function create($name, $url, $files = array()){
		$id = self::getID($name);
		
		// Handles old repositories as well
		$repository = new Repository($id);
		
		$repository->name = $name;
		$repository->url = $url;
		
		foreach($files as $file){
			$repository->add($file);
		}
		
		$repository->save();
		
		return $repository;
	}
	
	public static function exists($id){
		Redis::exists("Repository::$id");
	}
	
	protected static function getID($name){
		return sha1($name);
	}
	
	public function features(){
		$features = array();
		foreach($this->files as $id){
			$source = new Source($id);
			foreach($source->features as $attribute => $value){
				$features[$source->type][$attribute] += $value;
			}
		}
		
		// Average all the attributes
		foreach($features as $type => $attributes){
			foreach($attributes as $attribute => $value){
				$features[$type][$attribute] = ($value / count($this->files));
			}
		}
		
		return $features;
	}
	
	public function feature_keys(){	
		$keys = array();
		foreach($this->features() as $type => $attributes){
			foreach($attributes as $attribute => $value){
				$keys[] = "Match::{$type}::{$attribute}";
			}
		}
		
		return $keys;
	}
	
	protected function load($id){
		$data = Redis::hgetall("Repository::$id");
		
		foreach($data as $key => $val){
			if($key % 2 == 0){
				$this->$val = json_decode($data[$key + 1], true);
			}
		}
		
		$this->files = Redis::smembers("Repository.Source::$id");
		
		$this->id = $id;
	}
	
	public function save(){
		Redis::hmset("Repository::{$this->id}", 'name', json_encode($this->name), 'url', json_encode($this->url));
				
		foreach($this->features() as $type => $attributes){
			foreach($attributes as $attribute => $value){
				Redis::zadd("Match::{$type}::{$attribute}", $value, $this->id);
			}
		}
	}
}

?>