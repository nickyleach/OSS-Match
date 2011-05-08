<?

include 'Library/bootstrap.php';

$controller = Controller::create(Routing::controllerName());

$controller->beforeFilter();
$controller->filter(Routing::action(), Routing::args());
$controller->afterFilter();

?>