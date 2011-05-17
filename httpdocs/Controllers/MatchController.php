<?

class MatchController extends Controller {
	public function process(){
		if(isset($_POST['repository'])){
			$repoID = GitHub::parseRepository($_POST['repository']);
			self::redirect('Match', 'repository', array($repoID));
		}
	}
	
	public function repository($id = null){
		$repository = new Repository($id);
		die_dump($repository, $repository->features());
	}
}

?>