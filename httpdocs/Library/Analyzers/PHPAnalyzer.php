<?

class PHPAnalyzer implements Analyzer {
	protected $path;
	
	public function __construct($path){
		$this->path = $path;
	}
	
	public function analyze(){
		return array(
			'comment_presence'=>1,
			'comment_percentage'=>0.03,
			'indentation_baseline'=>1,
			'errors'=>0
		);
	}
}

?>