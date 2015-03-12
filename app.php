<?php

require __DIR__."/vendor/autoload.php";

use twiger\Router as Router;
use twiger\Twiger as Twiger;

//Including all Controllers
foreach (glob(__DIR__.'/src/control/*.php') as $filename){
	require $filename;
}

$routes = Spyc::YAMLLoad('app/config/routing.yml');

$constants = Spyc::YAMLLoad('app/config/constants.yml');

$requestUri = $_SERVER['REQUEST_URI'];

$router = new Router($routes);
$route = $router->handle($requestUri);


if (!is_null($route) && !isset($route['params']))
	$route['params'] = array();
if (!is_null($route) && !isset($route['route_params']))
	$route['route_params'] = array();


if (!is_null($route)){
	if (isset($route['controller'])) {
		
		$controlling = explode('::',$route['controller']);

		$controller = $controlling[0];
		$controller = 'control\\'.$controller;
		$controller = new $controller( array_merge($constants, $route['params']) );

		$method= $controlling[1];

		call_user_func_array(array($controller, $method), $route['route_params']);
		
	}elseif (isset($route['template'])) {

		$twiger = new Twiger( array_merge($constants, $route['params']) );
		$twiger->render('home.html.twig', $route['route_params'] );

	}
}else{
	$twiger = new Twiger();
	$twiger->render('404.html.twig', array('route' => $requestUri));
}