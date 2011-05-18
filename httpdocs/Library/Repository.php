<?

class Repository {
	public $id;
	public $files;
	public $name;
	public $score;
	public $url;
	
	protected $features;

	public function __construct($id){
		$this->load($id);
	}
	
	public function add($id){
		if(Source::exists($id)){
			$this->files[] = $id;
			Redis::sadd("Repository.Source::{$this->id}", $id);
		}
	}
	
	public static function create($name, $url = null, $files = array()){
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
		if($this->features) return $this->features;
		
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
		
		$this->features = $features;
		
		return $this->features;
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
	
	public function feature_score($key){
		$parts = explode("::", $key);
		$features = $this->features();
		
		return $features[$parts[1]][$parts[2]];
	}
	
	public function isUpload(){
		return !$this->url;
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
		
		// Can't recommend an uploaded file to a user, so don't bother inserting
		// it into the ranking sets, and go ahead and remove the reference to it
		// at some point in the near future
		if($this->isUpload()){
			Redis::expire("Repository::{$this->id}", UPLOAD_EXPIRE_TIME);
			return;
		}
		
		// If it is a repository, we want to insert it into the ranking sets	
		foreach($this->features() as $type => $attributes){
			foreach($attributes as $attribute => $value){
				Redis::zadd("Match::{$type}::{$attribute}", $value, $this->id);
			}
		}
	}
	
	public function similar(){
		$similarities = array();
		foreach($this->feature_keys() as $key){
			$keys = Util::mutlibulk_to_array(Redis::zrange($key, '0', '-1', 'withscores'));
			$base = $this->feature_score($key);
			$range = max($keys) - min($keys);
			
			$scores = array();
			foreach($keys as $key => $val){
				if($key == $this->id) continue;
				$similarities[$key] += ($range - abs($base - $val)) / $range;
			}
		}
		arsort($similarities);
		
		$repositories = array();
		foreach($similarities as $repoID => $score){
			$repository = new Repository($repoID);
			$repository->score = $score;
			
			$repositories[] = $repository;
		}
		
		return $repositories;
	}
}

?>