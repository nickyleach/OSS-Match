<?

class MatchController extends Controller {
	public function process(){
		if(isset($_POST['repository'])){
			GitHub::parseRepository($_POST['repository']);
		}
	}
}

?>