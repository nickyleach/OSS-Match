<?

class Source {
	public $id;
	public $features;
	public $type;
	
	public function __construct($id){
		$this->load($id);
	}
	
	public static function analyzable($path){
		$type = self::getType($path);
		$analyzerName = "{$type}Analyzer";
		
		if(!$type) return false;
		
		require_once "Library/Analyzers/Analyzer.php";
		return require_if_exists("Library/Analyzers/$analyzerName.php");
	}
	
	public static function create($path, $source = "upload"){
		$id = self::getID($path);
		
		// If a Source object for this file already exists, there is no need to recompute its attributes
		if(self::exists($id)) return new Source($id);
		
		$source = new Source($id);
		
		if(!self::analyzable($path)){
			throw new SourceCreateException("There is no Analyzer defined for this file");
		}
		
		$source->type = self::getType($path);
		$analyzerName = "{$source->type}Analyzer";
		
		$analyzer = new $analyzerName($path);
		$source->features = $analyzer->analyze();
		
		$source->save();
		
		return $source;
	}
	
	public static function exists($id){
		return Redis::exists("Source::$id");
	}
	
	protected static function getID($path){
		return sha1_file($path);
	}
	
	protected static function getType($path){
		return strtoupper(pathinfo($path, PATHINFO_EXTENSION));
	}
	
	protected function load($id){
		$data = Redis::hgetall("Source::$id");
		
		foreach($data as $key => $val){
			if($key % 2 == 0){
				$this->$val = json_decode($data[$key + 1], true);
			}
		}
		
		$this->id = $id;
	}
	
	public function save(){
		Redis::hmset("Source::{$this->id}", 'features', json_encode($this->features), 'type', json_encode($this->type));
	}
}

class SourceCreateException extends RuntimeException {}
class SourceLoadException extends RuntimeException {}
class SourceSaveException extends RuntimeException {}

?>