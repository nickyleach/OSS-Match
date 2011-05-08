<?

class LandingController extends Controller {
	public function index(){
		$this->loadView(new View('Common/header'), 'header');
		$this->loadView(new View('Landing/main'), 'main');
		$this->loadView(new View('Common/footer'), 'footer');
	}
}

?>