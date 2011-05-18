<?

class Upload {
	public static function processFile($path, $name){
		$repository = Repository::create(uniqid() . "/$name");
		
		if(Source::analyzable($path)){
			$source = Source::create($path, "upload");
			$repository->add($source->id);
		}
		
		$repository->save();
		
		return $repository->id;
	}
}

?>