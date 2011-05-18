<?

class MatchController extends Controller {
	public function process(){
		if(!empty($_POST['repository'])){
			try {
				$repoID = GitHub::parseRepository($_POST['repository']);
			} catch(Exception $e){
				self::redirect('Error', null, null, array('message'=>"Unable to process GitHub repository. Check the URL and ensure that the repository is public."));
				return;
			}
			
			self::redirect('Match', 'repository', array($repoID));
		} else if(!empty($_FILES['file']['tmp_name'])){
			$repoID = Upload::processFile($_FILES['file']['tmp_name'], $_FILES['file']['name']);
			self::redirect('Match', 'repository', array($repoID));
		} else {
			self::redirect();
		}
	}
	
	public function repository($id = null){
		$repository = new Repository($id);
		
		if(!count($repository->files)){
			self::redirect('Error', null, null, array('message'=>"Unable to offer recommendations based on this code"));
			return;
		}
		
		$this->loadView(new View('Common/header'), 'header');
		$this->loadView(new View('Match/repository', array(
			'repositories'=>$repository->similar(),
			'repository'=>$repository
		)), 'main');
		$this->loadView(new View('Common/footer'), 'footer');
	}
}

?>