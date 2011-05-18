<form action="<?= URL::create('Match', 'process'); ?>" method="post" enctype="multipart/form-data">
	<label for="repository">GitHub Repository URL:</label>
	<input type="text" name="repository" id="repository" />
	<p id="or">- or -</p>
	<label for="file">Upload File:</label>
	<input type="file" name="file" id="file" />
	
	<input type="submit" name="search" value="Match" />
</form>