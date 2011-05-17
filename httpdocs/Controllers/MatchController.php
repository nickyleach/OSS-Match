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
		
		$similarities = array();
		foreach($repository->feature_keys() as $key){
			$keys = Util::mutlibulk_to_array(Redis::zrange($key, '0', '-1', 'withscores'));
			$base = Redis::zscore($key, $repository->id);
			$range = max($keys) - min($keys);
			
			$scores = array();
			foreach($keys as $key => $val){
				if($key == $id) continue;
				$similarities[$key] += ($range - abs($base - $val)) / $range;
			}
		}
		
		arsort($similarities);
		
		$repositories = array();
		foreach($similarities as $repoID => $score){
			$repository = new Repository($repoID);
			$repositories[] = array(
				'name'=>$repository->name,
				'score'=>$score,
				'url'=>$repository->url
			);
		}
		
		$repository = new Repository($id);
		
		$this->loadView(new View('Common/header'), 'header');
		$this->loadView(new View('Match/repository', array(
			'repositories'=>$repositories,
			'orig_repository'=>array(
				'name' => $repository->name,
				'url' => $repository->url
			)
		)), 'main');
		$this->loadView(new View('Common/footer'), 'footer');
	}
}

?>