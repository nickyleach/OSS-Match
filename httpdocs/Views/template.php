<!doctype html>
<!--[if lt IE 7 ]> <html class="ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>	<html class="ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>	<html class="ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title><?= $title; ?></title>
  <meta name="description" content="<?= $meta['description']; ?>">
  <meta name="author" content="<?= $meta['author']; ?>">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="//ossmatch.nleach.com/css/style.css">

</head>

<body>

  <div id="container">
	<header>
	<? Template::render($header); ?>
	</header>
	<div id="main" role="main">
	<? Template::render($main); ?>
	</div>
	<footer>
	<? Template::render($footer); ?>
	</footer>
  </div> <!-- eo #container -->


  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.js"></script>

</body>
</html>