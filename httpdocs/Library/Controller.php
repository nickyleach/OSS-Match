<?

class Controller {
	// Execution
	protected $autoRender;
	protected $defaultToIndex;
	protected $name;

	// Path
	protected $action;
	protected $args;
	protected $extension;
	
	// View
	protected $meta;
	protected $title;
	protected $views;
	
	public function __construct() {
		// Execution
		$this->autoRender = true;
		$this->defaultToIndex = true;
		$this->name = str_replace("Controller", "", get_class());
		
		// Path
		$this->meta = array();
		$this->title = "OSS Match";
		$this->views = array();
	}
	
	public function afterFilter(){}
	
	public function beforeFilter(){}
	
	public static function create($name){
		$controllerName = "{$name}Controller";
		$controllerFile = "Controllers/$controllerName.php";
		
		if(file_exists($controllerFile))
			require_once $controllerFile;
		
		if(class_exists($controllerName, false)){
			return new $controllerName();
		} else {
			throw new Exception("Undefined controller - $controllerName");
		}
	}
	
	public function filter($action, $args = array()){
		$this->action = ( $action ? $action : "index" );
		$this->args = ( $args ? $args : array() );
		$this->extension = pathinfo($this->args[count($this->args) - 1], PATHINFO_EXTENSION);
		
		if($this->extension){
			$this->args[count($this->args) - 1] = str_replace(".{$this->extension}", '', $this->args[count($this->args) - 1]);
		} else {
			$this->extension = "html";
		}
		
		// Remove empty values from the args array
		foreach($this->args as $key=>$arg){
			if(empty($arg) || $arg == '') unset($this->args[$key]);
		}
		
		if(!method_exists($this, $this->action)){
			if($this->defaultToIndex && method_exists($this, 'index')){
				array_unshift($this->args, $this->action);
				$this->action = 'index';
				$this->filter($this->action, $this->args);
				return;
			}
			throw new Exception("Undefined method - {$this->name}/{$this->action}");
		}
		
		if($this->beforeFilter() === false) return;
		
		call_user_method_array($this->action, $this, $this->args);
		
		$this->afterFilter();

		if($this->autoRender) $this->render();
	}
	
	public function loadView($view, $outlet){
		$this->views[$outlet][] = $view;
	}
	
	public static function redirect($controller, $action = null, $args = null, $params = null, $hard = false){
		$url = URL::create($controller, $action, $args, $params);
		
		// do actual redirect
		if($hard){
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: ' . $url);
		} else {
			header('Cache-Control:no-store, no-cache, must-revalidate, post-check=0, pre-check=0  ');
			header('Pragma: no-cache ');	   
			header('Location: ' . $url);
		}
		
		exit;
	}
	
	public function render(){
		$meta = $this->meta;
		$title = $this->title;
		$views = $this->views;
		
		Util::execute(function() use ($meta, $title, $views){
			extract($views);
			include 'Views/template.php';
		});
		
		flush();
	}
}

?>