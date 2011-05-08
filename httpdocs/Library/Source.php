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
		
		require_once "Library/Analyzers/Analyzer.php";
		return require_if_exists("Library/Analyzers/$analyzerName.php");
	}
	
	public static function create($path, $source = "upload"){
		$id = self::getID($path);
		
		// If a Source object for this file already exists, there is no need to recompute its attributes
		if(self::exists($id)) return new Source($id);
		
		if(!self::analyzable($path)){
			throw new SourceCreateException("There is no Analyzer defined for this file");
		}
		
		$type = self::getType($path);
		$analyzerName = "{$type}Analyzer";
		
		$analyzer = new $analyzerName($path);
		$features = $analyzer->analyze();
		
		self::save($id, compact('features', 'type'));
		
		return new Source($id);
	}
	
	protected static function exists($id){
		Redis::exists("Source::$id");
	}
	
	protected static function getID($path){
		return sha1_file($path);
	}
	
	protected static function getType($path){
		return strtoupper(pathinfo($path, PATHINFO_EXTENSION));
	}
	
	protected function load($id){
		$data = Redis::get("Source::$id");
		
		if(!(isset($data['features']) && isset($data['type']))) throw new SourceLoadException("Invalid Source retrieved from storage");
		
		foreach($data as $field => $val){
			$this->$field = $val;
		}
		
		$this->id = $id;
	}
	
	protected static function save($id, $data){
		if(!(isset($data['features']) && isset($data['type']))) throw new SourceSaveException("Invalid Source saved to storage");
		
		Redis::set("Source::$id", $data);
	}
}

class SourceCreateException extends RuntimeException {}
class SourceLoadException extends RuntimeException {}
class SourceSaveException extends RuntimeException {}

?>