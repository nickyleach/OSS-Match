<?

class GitHub {
	public static function parseRepository($url){
		$response = Util::exec_script("GitHub/pull_repository", self::urlToGit($url));
		
		if($response['return'] != 0) throw new ParseException("Unable to parse the repository at URL '$url'");
		
		$repository = Repository::create(self::username($url) . '/' . self::repository($url), $url);
		
		$files = FileSystem::files($response['output'][0], true);
		foreach($files as $path){
			if(Source::analyzable($path)){
				$source = Source::create($path, "github");
				$repository->add($source->id);
			}
		}
		
		$repository->save();
		
		Util::exec_script("GitHub/cleanup_repository", self::urlToGit($url), true);
		
		return $repository->id;
	}
	
	public static function urlToGit($url){
		return 'git://' . str_replace('https://', '', str_replace('http://', '', $url)) . '.git';
	}
	
	public static function username($url){
		$parts = explode('/', str_replace('https://', '', str_replace('http://', '', $url)));
		return $parts[1];
	}
	
	public static function repository($url){
		$parts = explode('/', str_replace('https://', '', str_replace('http://', '', $url)));
		return $parts[2];
	}
}

class ParseException extends RuntimeException {};

?>