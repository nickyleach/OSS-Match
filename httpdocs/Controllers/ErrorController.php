<?

class ErrorController extends Controller {
	
	public function index(){
		$this->loadView(new View('Common/header'), 'header');
		$this->loadView(new View('Common/footer'), 'footer');
		$this->loadView(new View('Error/main', array(
			'message'=>$_GET['message']
		)), 'main');
	}
}

?>