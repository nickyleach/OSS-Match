<?

include 'Library/bootstrap.php';

try {
	$controller = Controller::create(Routing::controllerName());
} catch (Exception $e){
	Util::redirect('404.html');
}

$controller->beforeFilter();
$controller->filter(Routing::action(), Routing::args());
$controller->afterFilter();

?>