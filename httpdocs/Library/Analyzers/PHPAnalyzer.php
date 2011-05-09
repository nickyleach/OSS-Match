<?

class PHPAnalyzer extends Analyzer {
	protected $path;
	
	public function __construct($path){
		$this->path = $path;
	}
	
	public function analyze(){
		return array_merge($this->codeSniffer());
	}
	
	protected function codeSniffer(){
		$response = Util::exec_script('Analyzers/php_codesniffer', $this->path);
		if($response['return'] != 0) return array();
		
		$xml = simplexml_load_file($response['output'][0]);
		$smells = $xml->file->children();
		
		$attributes = array();
		foreach($smells as $smell){
			$attributes[(string)$smell['source']]++;
		}
		
		return $attributes;
	}
}

?>