<?

class LandingController extends Controller {
	public function beforeFilter(){
		parent::beforeFilter();
	}
	
	public function index(){
		$this->loadView(new View('Common/header'), 'header');
		$this->loadView(new View('Common/footer'), 'footer');
		$this->loadView(new View('Landing/main'), 'main');
	}
	
	public function about(){
		$this->loadView(new View('Common/header'), 'header');
		$this->loadView(new View('Common/footer'), 'footer');
		$this->loadView(new View('Landing/about'), 'main');
	}
}

?>